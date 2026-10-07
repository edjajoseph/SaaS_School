<?php

namespace App\Modules\School\Http\Controllers\Web\Accounting;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\Payment;
use App\Modules\School\Models\StudentFeeAccount;
use App\Modules\School\Models\SchoolClass;
use App\Modules\School\Models\StudentFeeSchedule;
use App\Modules\School\Models\Student;
use App\Modules\School\Models\School;
use App\Modules\School\Models\AcademicYear; // Ajustez selon votre Model d'Année Académique
use Barryvdh\DomPDF\Facade\Pdf;

// Importation des Exports modulaires
use App\Modules\School\Exports\OverdueExport;
use App\Modules\School\Exports\PaymentsPeriodExport;
use App\Modules\School\Exports\RecoveryRateExport;

use Illuminate\Http\Request;
use Carbon\Carbon;

class FinancialReportController extends Controller
{
    
    /**
     * 1. Journal de Caisse Quotidien
     */
    public function dailyCashJournal(Request $request)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $schoolId = $request->input('school_id');

        $receipts = Payment::with(['account.registration.student', 'account.registration.schoolClass'])
            ->whereDate('created_at', $date)
            ->when($schoolId, function ($q) use ($schoolId) {
                $q->whereHas('account', fn($sub) => $sub->where('school_id', $schoolId));
            })
            ->get();

        // Filtrage insensible à la casse pour les espèces
        $totalCash = $receipts->filter(function ($receipt) {
            $mode = strtoupper(trim($receipt->payment_method));
            return in_array($mode, ['CASH', 'ESPÈCES', 'ESPECES','cash','Espèces']);
        })->sum('amount');

        // Tout le reste va dans Banque & Mobile Money
        $totalOther = $receipts->filter(function ($receipt) {
            $mode = strtoupper(trim($receipt->payment_method));
            return !in_array($mode, ['CASH', 'ESPÈCES', 'ESPECES','cash','Espèces']);
        })->sum('amount');

        $grandTotal = $receipts->sum('amount');

        return view('School::accounting.reports.daily_cash', compact(
            'receipts', 
            'date', 
            'totalCash', 
            'totalOther', 
            'grandTotal'
        ));
    }

    /**
     * Génère le Procès-Verbal (PV) de Clôture de Caisse Quotidien au format PDF
     */
    public function exportPdfDailyCashPv(Request $request)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $schoolId = $request->input('school_id');

        $receipts = Payment::with([
                'account.registration.student.personne', 
                'account.registration.schoolClass'
            ])
            ->whereDate('created_at', $date)
            ->when($schoolId, function ($q) use ($schoolId) {
                $q->whereHas('account', fn($sub) => $sub->where('school_id', $schoolId));
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Ventilation par mode de règlement
        $totalCash = $receipts->whereIn('payment_method', ['CASH', 'Espèces'])->sum('amount');
        $totalMobile = $receipts->whereIn('payment_method', ['WAVE', 'OM', 'MTN', 'MOBILE_MONEY'])->sum('amount');
        $totalBank = $receipts->whereIn('payment_method', ['CHEQUE', 'VIREMENT', 'BANK'])->sum('amount');
        $grandTotal = $receipts->sum('amount');

        // Récupération de l'établissement (si applicable)
        $school = $schoolId ? School::find($schoolId) : null;

        $pdf = Pdf::loadView('school::accounting.reports.daily_cash_pv_pdf', compact(
            'receipts',
            'date',
            'totalCash',
            'totalMobile',
            'totalBank',
            'grandTotal',
            'school'
        ))->setPaper('a4', 'portrait');

        return $pdf->stream('PV_Cloture_Caisse_' . $date . '.pdf');
    }

    /**
     * 2. État des Impayés (Créances par classe et année académique)
     */
    public function overdueAccounts(Request $request)
    {
        $classId = $request->input('school_class_id');
        $academicYearId = $request->input('academic_year_id');

        $accounts = StudentFeeAccount::with(['registration.student', 'registration.schoolClass', 'schedules'])
            ->where('balance', '>', 0) // Ont un solde restant dû
            ->when($classId, function ($q) use ($classId) {
                $q->whereHas('registration', fn($sub) => $sub->where('school_class_id', $classId));
            })
            ->when($academicYearId, function ($q) use ($academicYearId) {
                $q->whereHas('registration', fn($sub) => $sub->where('academic_year_id', $academicYearId));
            })
            ->get();

        $classes = SchoolClass::all();
        $academicYears = AcademicYear::orderBy('created_at', 'desc')->get();

        return view('School::accounting.reports.overdue', compact(
            'accounts', 
            'classes', 
            'academicYears', 
            'classId', 
            'academicYearId'
        ));
    }

    /**
     * Génère le rapport PDF de l'état des impayés et créances
     */
    public function overdueAccountsPdf(Request $request)
    {
        $classId = $request->input('school_class_id');
        $academicYearId = $request->input('academic_year_id');

        $accounts = StudentFeeAccount::with(['registration.student.personne', 'registration.schoolClass', 'schedules'])
            ->where('balance', '>', 0)
            ->when($classId, function ($q) use ($classId) {
                $q->whereHas('registration', fn($sub) => $sub->where('school_class_id', $classId));
            })
            ->when($academicYearId, function ($q) use ($academicYearId) {
                $q->whereHas('registration', fn($sub) => $sub->where('academic_year_id', $academicYearId));
            })
            ->get();

        $selectedClass = $classId ? SchoolClass::find($classId) : null;
        $selectedYear = $academicYearId ? AcademicYear::find($academicYearId) : null;
        
        $totalOverdue = $accounts->sum('balance');
        $totalDiscounts = $accounts->sum('discount_amount');

        $pdf = Pdf::loadView('school::accounting.reports.overdue_pdf', compact(
            'accounts',
            'selectedClass',
            'selectedYear',
            'totalOverdue',
            'totalDiscounts'
        ))->setPaper('a4', 'landscape');

        return $pdf->stream('etat_impayes_' . date('Y_m_d') . '.pdf');
    }

    /**
     * 3. Synthèse & Taux de Recouvrement par Classe
     */
    public function recoveryRate(Request $request)
    {
        $academicYearId = $request->input('academic_year_id');

        // On précharge les inscriptions filtrées par l'année académique si renseignée
        $classes = SchoolClass::with(['registrations' => function ($q) use ($academicYearId) {
            if ($academicYearId) {
                $q->where('academic_year_id', $academicYearId);
            }
            $q->with('feeAccount');
        }])->get();

        $summary = $classes->map(function ($schoolClass) {
            $totalDue = 0;
            $totalPaid = 0;
            $totalDiscount = 0;

            foreach ($schoolClass->registrations as $registration) {
                if ($registration->feeAccount) {
                    $totalDue += $registration->feeAccount->total_due;
                    $totalPaid += $registration->feeAccount->total_paid;
                    $totalDiscount += $registration->feeAccount->discount_amount;
                }
            }

            $netDue = max(0, $totalDue - $totalDiscount);
            $rate = $netDue > 0 ? ($totalPaid / $netDue) * 100 : 0;

            return [
                'class_name' => $schoolClass->name ?? $schoolClass->libelle,
                'total_students' => $schoolClass->registrations->count(),
                'total_due' => $totalDue,
                'total_discount' => $totalDiscount,
                'net_due' => $netDue,
                'total_paid' => $totalPaid,
                'balance' => max(0, $netDue - $totalPaid),
                'rate' => round($rate, 2),
            ];
        });

        $academicYears = AcademicYear::orderBy('created_at', 'desc')->get();

        return view('School::accounting.reports.recovery_rate', compact(
            'summary',
            'academicYears',
            'academicYearId'
        ));
    }

    /**
     * Génère le rapport PDF du Taux de Recouvrement Global
     */
    public function recoveryRatePdf(Request $request)
    {
        $academicYearId = $request->input('academic_year_id');

        $classes = SchoolClass::with(['registrations' => function ($q) use ($academicYearId) {
            if ($academicYearId) {
                $q->where('academic_year_id', $academicYearId);
            }
            $q->with('feeAccount');
        }])->get();

        $summary = $classes->map(function ($schoolClass) {
            $totalDue = 0;
            $totalPaid = 0;
            $totalDiscount = 0;

            foreach ($schoolClass->registrations as $registration) {
                if ($registration->feeAccount) {
                    $totalDue += $registration->feeAccount->total_due;
                    $totalPaid += $registration->feeAccount->total_paid;
                    $totalDiscount += $registration->feeAccount->discount_amount;
                }
            }

            $netDue = max(0, $totalDue - $totalDiscount);
            $rate = $netDue > 0 ? ($totalPaid / $netDue) * 100 : 0;

            return [
                'class_name' => $schoolClass->name ?? $schoolClass->libelle,
                'total_students' => $schoolClass->registrations->count(),
                'total_due' => $totalDue,
                'total_discount' => $totalDiscount,
                'net_due' => $netDue,
                'total_paid' => $totalPaid,
                'balance' => max(0, $netDue - $totalPaid),
                'rate' => round($rate, 2),
            ];
        });

        $selectedYear = $academicYearId ? AcademicYear::find($academicYearId) : null;
        $globalNet = $summary->sum('net_due');
        $globalPaid = $summary->sum('total_paid');
        $globalBalance = $summary->sum('balance');
        $globalRate = $globalNet > 0 ? round(($globalPaid / $globalNet) * 100, 2) : 0;

        $pdf = Pdf::loadView('school::accounting.reports.recovery_rate_pdf', compact(
            'summary',
            'selectedYear',
            'globalNet',
            'globalPaid',
            'globalBalance',
            'globalRate'
        ))->setPaper('a4', 'landscape');

        return $pdf->stream('taux_recouvrement_' . date('Y_m_d') . '.pdf');
    }

    /**
     * 4. Rapport des Bourses, Exonérations et Remises Accordées
     */
    public function discountsReport(Request $request)
    {
        $classId = $request->input('school_class_id');
        $academicYearId = $request->input('academic_year_id');

        $accounts = StudentFeeAccount::with([
            'registration.student.personne', 
            'registration.schoolClass',
            'registration.academicYear'
        ])
        ->where('discount_amount', '>', 0)
        ->when($classId, function ($q) use ($classId) {
            $q->whereHas('registration', fn($sub) => $sub->where('school_class_id', $classId));
        })
        ->when($academicYearId, function ($q) use ($academicYearId) {
            $q->whereHas('registration', fn($sub) => $sub->where('academic_year_id', $academicYearId));
        })
        ->get();

        $classes = SchoolClass::all();
        $academicYears = AcademicYear::orderBy('created_at', 'desc')->get();
        $totalDiscounts = $accounts->sum('discount_amount');

        return view('school::accounting.reports.discounts', compact(
            'accounts', 
            'classes', 
            'academicYears', 
            'classId', 
            'academicYearId', 
            'totalDiscounts'
        ));
    }

    /**
     * Génère le rapport PDF des bourses, exonérations et remises
     */
    public function discountsReportPdf(Request $request)
    {
        $classId = $request->input('school_class_id');
        $academicYearId = $request->input('academic_year_id');

        $accounts = StudentFeeAccount::with([
            'registration.student.personne', 
            'registration.schoolClass',
            'registration.academicYear'
        ])
        ->where('discount_amount', '>', 0)
        ->when($classId, function ($q) use ($classId) {
            $q->whereHas('registration', fn($sub) => $sub->where('school_class_id', $classId));
        })
        ->when($academicYearId, function ($q) use ($academicYearId) {
            $q->whereHas('registration', fn($sub) => $sub->where('academic_year_id', $academicYearId));
        })
        ->get();

        $selectedClass = $classId ? SchoolClass::find($classId) : null;
        $selectedYear = $academicYearId ? AcademicYear::find($academicYearId) : null;
        $totalDiscounts = $accounts->sum('discount_amount');

        $pdf = Pdf::loadView('school::accounting.reports.discounts_pdf', compact(
            'accounts', 
            'selectedClass', 
            'selectedYear',
            'totalDiscounts'
        ))->setPaper('a4', 'landscape');

        return $pdf->stream('rapport_exonerations_' . date('Y_m_d') . '.pdf');
    }

    /**
     * 5. Rapport Prévisionnel des Encaissements (Trésorerie à venir)
     */
    public function cashForecastReport(Request $request)
    {
        $query = StudentFeeSchedule::with([
            'account.student.personne',
            'account.registration.schoolClass',
            'account.registration.academicYear'
        ])->where('is_paid', false);

        if ($request->filled('student_id')) {
            $query->whereHas('account.registration', function ($q) use ($request) {
                $q->where('student_id', $request->student_id);
            });
        }

        if ($request->filled('school_class_id')) {
            $query->whereHas('account.registration', function ($q) use ($request) {
                $q->where('school_class_id', $request->school_class_id);
            });
        }

        if ($request->filled('academic_year_id')) {
            $query->whereHas('account.registration', function ($q) use ($request) {
                $q->where('academic_year_id', $request->academic_year_id);
            });
        }

        $schedules = $query->orderBy('due_date', 'asc')->get();

        $monthlyForecast = $schedules->groupBy(function ($schedule) {
            return Carbon::parse($schedule->due_date)->format('Y-m');
        })->map(function ($items) {
            return [
                'count' => $items->count(),
                'total_expected' => $items->sum(function ($item) {
                    return ($item->amount - $item->discount_amount) - $item->paid_amount;
                }),
            ];
        });

        $totalExpectedForecast = $schedules->sum(function ($item) {
            return ($item->amount - $item->discount_amount) - $item->paid_amount;
        });

        $students = Student::with('personne')->get();
        $classes = SchoolClass::all();
        $academicYears = AcademicYear::orderBy('created_at', 'desc')->get();

        return view('school::accounting.reports.cash_forecast', compact(
            'schedules', 
            'monthlyForecast', 
            'totalExpectedForecast',
            'students',
            'classes',
            'academicYears'
        ));
    }

    /**
     * Génère le rapport PDF du prévisionnel de trésorerie
     */
    public function cashForecastPdf(Request $request)
    {
        $query = StudentFeeSchedule::with([
            'account.student.personne',
            'account.registration.schoolClass',
            'account.registration.academicYear'
        ])->where('is_paid', false);

        if ($request->filled('student_id')) {
            $query->whereHas('account.registration', function ($q) use ($request) {
                $q->where('student_id', $request->student_id);
            });
        }

        if ($request->filled('school_class_id')) {
            $query->whereHas('account.registration', function ($q) use ($request) {
                $q->where('school_class_id', $request->school_class_id);
            });
        }

        if ($request->filled('academic_year_id')) {
            $query->whereHas('account.registration', function ($q) use ($request) {
                $q->where('academic_year_id', $request->academic_year_id);
            });
        }

        $schedules = $query->orderBy('due_date', 'asc')->get();

        $monthlyForecast = $schedules->groupBy(function ($schedule) {
            return Carbon::parse($schedule->due_date)->format('Y-m');
        })->map(function ($items) {
            return [
                'count' => $items->count(),
                'total_expected' => $items->sum(function ($item) {
                    return ($item->amount - $item->discount_amount) - $item->paid_amount;
                }),
            ];
        });

        $totalExpectedForecast = $schedules->sum(function ($item) {
            return ($item->amount - $item->discount_amount) - $item->paid_amount;
        });

        $pdf = Pdf::loadView('school::accounting.reports.cash_forecast_pdf', compact('schedules', 'monthlyForecast', 'totalExpectedForecast'))
                ->setPaper('a4', 'landscape');

        return $pdf->stream('previsionnel_tresorerie_' . date('Y_m_d') . '.pdf');
    }

   
    /**
     * 6. Fiche / Relevé de compte individuel étudiant
     */
    public function studentStatement(Request $request)
    {
        $studentId = $request->input('student_id');
        $academicYearId = $request->input('academic_year_id');
        
        $student = null;
        $account = null;

        if ($studentId) {
            $student = Student::with('personne')->find($studentId);
            
            $accountQuery = StudentFeeAccount::with([
                'registration.schoolClass',
                'registration.academicYear',
                'payments' => fn($q) => $q->orderBy('created_at', 'asc'),
                'schedules' => fn($q) => $q->orderBy('due_date', 'asc')
            ])->whereHas('registration', fn($q) => $q->where('student_id', $studentId));

            // Filtrer par année académique si sélectionnée
            if ($academicYearId) {
                $accountQuery->whereHas('registration', fn($q) => $q->where('academic_year_id', $academicYearId));
            }

            $account = $accountQuery->first();
        }

        $students = Student::with('personne')->get();
        $academicYears = AcademicYear::orderBy('created_at', 'desc')->get();

        return view('school::accounting.reports.student_statement', compact(
            'student', 
            'account', 
            'students', 
            'academicYears', 
            'studentId', 
            'academicYearId'
        ));
    }

    /**
     * Génère le PDF du Relevé de Compte Étudiant
     */
    public function studentStatementPdf(Request $request)
    {
        $studentId = $request->input('student_id');
        $academicYearId = $request->input('academic_year_id');

        $accountQuery = StudentFeeAccount::with([
            'registration.student.personne',
            'registration.schoolClass',
            'registration.academicYear',
            'payments' => fn($q) => $q->orderBy('created_at', 'asc'),
            'schedules' => fn($q) => $q->orderBy('due_date', 'asc')
        ])->whereHas('registration', fn($q) => $q->where('student_id', $studentId));

        if ($academicYearId) {
            $accountQuery->whereHas('registration', fn($q) => $q->where('academic_year_id', $academicYearId));
        }

        $account = $accountQuery->firstOrFail();

        $pdf = Pdf::loadView('school::accounting.reports.student_statement_pdf', compact('account'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('releve_compte_' . ($account->registration->student->matricule ?? $account->id) . '.pdf');
    }

    /**
     * 7. Arrêté de Caisse Journalier
     */
    public function cashClosure(Request $request)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $academicYearId = $request->input('academic_year_id');

        $query = Payment::with(['account.registration.student.personne', 'account.registration.schoolClass'])
            ->whereDate('created_at', $date);

        // Filtre par année académique si sélectionnée
        if ($academicYearId) {
            $query->whereHas('account.registration', fn($q) => $q->where('academic_year_id', $academicYearId));
        }

        $payments = $query->get();

        // Support de payment_method ou payment_method selon le schéma de base de données
        $byMode = [
            'CASH' => $payments->filter(fn($p) => in_array($p->payment_method ?? $p->payment_method, ['CASH', 'ESPECES', 'ESPECE','cash']))->sum('amount'),
            'MOBILE_MONEY' => $payments->filter(fn($p) => in_array($p->payment_method ?? $p->payment_method, ['ORANGE_MONEY', 'WAVE', 'MTN_MONEY', 'MOOV_MONEY', 'MOBILE_MONEY', 'TPE', 'mobile_money']))->sum('amount'),
            'CHECK' => $payments->filter(fn($p) => in_array($p->payment_method ?? $p->payment_method, ['CHECK', 'CHEQUE', 'cheque']))->sum('amount'),
            'TRANSFER' => $payments->filter(fn($p) => in_array($p->payment_method ?? $p->payment_method, ['TRANSFER', 'VIREMENT','bank_transfer']))->sum('amount'),
        ];

        $totalCollected = $payments->sum('amount');
        $academicYears = AcademicYear::orderBy('created_at', 'desc')->get();

        return view('school::accounting.reports.cash_closure', compact(
            'payments', 
            'date', 
            'byMode', 
            'totalCollected', 
            'academicYears', 
            'academicYearId'
        ));
    }

    /**
     * Génère le PDF de l'Arrêté de Caisse Journalier
     */
    public function cashClosurePdf(Request $request)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $academicYearId = $request->input('academic_year_id');

        $query = Payment::with(['account.registration.student.personne', 'account.registration.schoolClass'])
            ->whereDate('created_at', $date);

        if ($academicYearId) {
            $query->whereHas('account.registration', fn($q) => $q->where('academic_year_id', $academicYearId));
        }

        $payments = $query->get();

        $byMode = [
            'CASH' => $payments->filter(fn($p) => in_array($p->payment_method ?? $p->payment_method, ['CASH', 'ESPECES', 'ESPECE']))->sum('amount'),
            'MOBILE_MONEY' => $payments->filter(fn($p) => in_array($p->payment_method ?? $p->payment_method, ['ORANGE_MONEY', 'WAVE', 'MTN_MONEY', 'MOOV_MONEY', 'MOBILE_MONEY', 'TPE']))->sum('amount'),
            'CHECK' => $payments->filter(fn($p) => in_array($p->payment_method ?? $p->payment_method, ['CHECK', 'CHEQUE']))->sum('amount'),
            'TRANSFER' => $payments->filter(fn($p) => in_array($p->payment_method ?? $p->payment_method, ['TRANSFER', 'VIREMENT']))->sum('amount'),
        ];

        $totalCollected = $payments->sum('amount');

        $pdf = Pdf::loadView('school::accounting.reports.cash_closure_pdf', compact('payments', 'date', 'byMode', 'totalCollected'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('arrete_caisse_' . $date . '.pdf');
    }

    /**
     * 8. Liste des Autorisations aux Examens
     */
    public function examClearance(Request $request)
    {
        $classId = $request->input('school_class_id');
        $academicYearId = $request->input('academic_year_id');
        $threshold = $request->input('threshold', 75);

        $classes = SchoolClass::all();
        $academicYears = AcademicYear::orderBy('created_at', 'desc')->get();
        $studentsClearance = collect();

        if ($classId) {
            $accountQuery = StudentFeeAccount::with(['registration.student.personne', 'registration.schoolClass'])
                ->whereHas('registration', fn($q) => $q->where('school_class_id', $classId));

            if ($academicYearId) {
                $accountQuery->whereHas('registration', fn($q) => $q->where('academic_year_id', $academicYearId));
            }

            $accounts = $accountQuery->get();

            $studentsClearance = $accounts->map(function ($account) use ($threshold) {
                $netDue = max(0, $account->total_due - $account->discount_amount);
                $rate = $netDue > 0 ? ($account->total_paid / $netDue) * 100 : 100;
                $isAllowed = $rate >= $threshold;

                return [
                    'student' => $account->registration->student,
                    'class' => $account->registration->schoolClass,
                    'total_due' => $netDue,
                    'total_paid' => $account->total_paid,
                    'balance' => $account->balance,
                    'rate' => round($rate, 2),
                    'is_allowed' => $isAllowed
                ];
            });
        }

        return view('school::accounting.reports.exam_clearance', compact(
            'studentsClearance', 
            'classes', 
            'academicYears', 
            'classId', 
            'academicYearId', 
            'threshold'
        ));
    }

    /**
     * Génère le PDF des Autorisations aux Examens
     */
    public function examClearancePdf(Request $request)
    {
        $classId = $request->input('school_class_id');
        $academicYearId = $request->input('academic_year_id');
        $threshold = $request->input('threshold', 75);

        $selectedClass = SchoolClass::findOrFail($classId);

        $accountQuery = StudentFeeAccount::with(['registration.student.personne', 'registration.schoolClass', 'registration.academicYear'])
            ->whereHas('registration', fn($q) => $q->where('school_class_id', $classId));

        if ($academicYearId) {
            $accountQuery->whereHas('registration', fn($q) => $q->where('academic_year_id', $academicYearId));
        }

        $accounts = $accountQuery->get();

        $studentsClearance = $accounts->map(function ($account) use ($threshold) {
            $netDue = max(0, $account->total_due - $account->discount_amount);
            $rate = $netDue > 0 ? ($account->total_paid / $netDue) * 100 : 100;

            return [
                'student' => $account->registration->student,
                'class' => $account->registration->schoolClass,
                'total_due' => $netDue,
                'total_paid' => $account->total_paid,
                'balance' => $account->balance,
                'rate' => round($rate, 2),
                'is_allowed' => $rate >= $threshold
            ];
        });

        $pdf = Pdf::loadView('school::accounting.reports.exam_clearance_pdf', [
            'studentsClearance' => $studentsClearance,
            'schoolClass' => $selectedClass,
            'threshold' => $threshold
        ])->setPaper('a4', 'landscape');

        return $pdf->stream('autorisations_examens_' . $selectedClass->id . '.pdf');
    }

    /**
     * PDF 1 : Reçu de paiement individuel
     */
    public function exportPaymentReceiptPdf($paymentId)
    {
        $payment = Payment::with(['account.registration.student', 'account.registration.schoolClass'])
            ->findOrFail($paymentId);

        $pdf = Pdf::loadView('school::accounting.pdf.payment_receipt', compact('payment'))
            ->setPaper('a5', 'landscape');

        return $pdf->download('Recu_Paiement_' . $payment->receipt_number . '.pdf');
    }

    /**
     * PDF 2 : Fiche de compte financier individuel de l'étudiant
     */
    public function exportStudentAccountPdf(Request $request)
    {
        // Récupération de l'ID passé en query string (?student_id=12 ou ?account_id=12)
        $studentId = $request->input('student_id');

        // Récupération du compte lié à l'étudiant
        $account = StudentFeeAccount::with([
            'registration.student',
            'registration.schoolClass',
            'payments' => fn($q) => $q->orderBy('paid_at', 'asc'),
            'schedules' => fn($q) => $q->orderBy('due_date', 'asc')
        ])
        ->whereHas('registration', fn($q) => $q->where('student_id', $studentId))
        ->firstOrFail();

        $pdf = Pdf::loadView('School::accounting.reports.student_statement_pdf', compact('account'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Fiche_Compte_' . ($account->registration->student->matricule ?? $account->id) . '.pdf');
    }

    /**
     * PDF 3 : Procès-verbal de clôture de caisse quotidienne
     */
    public function exportDailyCashPvPdf(Request $request)
    {
        $date = $request->input('date', date('Y-m-d'));
        
        $payments = Payment::with(['account.registration.student.personne', 'account.registration.schoolClass'])
            ->whereDate('paid_at', $date)
            ->get();

        $totalCash = $payments->where('payment_method', 'cash')->sum('amount');
        $totalBank = $payments->where('payment_method', '!=', 'cash')->sum('amount');
        $totalCollected = $payments->sum('amount');

        $pdf = Pdf::loadView('school::accounting.pdf.daily_cash_pv', compact('payments', 'date', 'totalCash', 'totalBank', 'totalCollected'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('PV_Cloture_Caisse_' . $date . '.pdf');
    }
}