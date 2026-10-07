<?php

namespace App\Modules\School\Http\Controllers\Web\Accounting;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\AccountingJournal;
use App\Modules\School\Models\StudentFeeAccount;
use App\Modules\School\Models\Payment;
use App\Modules\School\Models\PaymentItem;
use App\Modules\School\Models\StudentFeeSchedule;
use App\Modules\School\Services\TreasuryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    protected $treasuryService;

    public function __construct(TreasuryService $treasuryService)
    {
        $this->treasuryService = $treasuryService;
    }

    public function index(Request $request)
    {
        $query = StudentFeeAccount::with([
            'student.personne',
            'registration.schoolClass',
            'schedules.feePlan',
            'feePlan'
        ]);

        if ($request->filled('search')) {
            $search = trim($request->search);
            
            $query->where(function ($q) use ($search) {
                $q->whereHas('student.personne', function ($personQuery) use ($search) {
                    $personQuery->where('nom', 'like', "%{$search}%")
                                ->orWhere('prenoms', 'like', "%{$search}%");
                })
                ->orWhereHas('student', function ($studentQuery) use ($search) {
                    $studentQuery->where('matricule', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $statsQuery = clone $query;

        $totalGross    = (float) $statsQuery->sum('total_due');
        $totalDiscount = (float) $statsQuery->sum('discount_amount');
        $totalPaid     = (float) $statsQuery->sum('total_paid');
        $totalBalance  = (float) $statsQuery->sum('balance');

        $totalNet     = max(0, $totalGross - $totalDiscount);
        $recoveryRate = $totalNet > 0 ? round(($totalPaid / $totalNet) * 100, 1) : 0;

        $stats = [
            'total_gross'   => $totalGross,
            'total_discount'=> $totalDiscount,
            'total_net'     => $totalNet,
            'total_paid'    => $totalPaid,
            'total_balance' => $totalBalance,
            'recovery_rate' => $recoveryRate,
        ];

        $accounts = $query->paginate(15)->withQueryString();

        return view('school::accounting.index', compact('accounts', 'stats'));
    }

    public function show($id)
    {
        $account = StudentFeeAccount::with([
            'registration.student.personne', 
            'registration.schoolClass', 
            'schedules', 
            'payments.cashier',
            'payments.journal'
        ])->findOrFail($id);

        $treasuryJournals = AccountingJournal::with('defaultAccount')
            ->whereIn('type', ['bank', 'cash'])
            ->where('is_active', true)
            ->get();

        return view('school::accounting.show', compact('account', 'treasuryJournals'));
    }

    public function store(Request $request, $id)
    {
        $account = StudentFeeAccount::with('student.personne')->findOrFail($id);
        $remainingBalance = max(0, ($account->total_due - $account->discount_amount) - $account->total_paid);

        if ($remainingBalance <= 0) {
            return redirect()->back()->withErrors(['amount' => 'Ce compte est déjà entièrement soldé !']);
        }

        $validated = $request->validate([
            'student_fee_schedule_id' => 'required|exists:student_fee_schedules,id',
            'journal_id'              => 'required|exists:accounting_journals,id',
            'amount'                  => 'required|numeric|min:1|max:' . $remainingBalance,
            'payment_method'          => 'required|in:cash,mobile_money,bank_transfer,cheque',
            'transaction_reference'   => 'nullable|string|max:100',
            'notes'                   => 'nullable|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($account, $validated) {
                // 1. Contrôle du Journal et de son compte comptable par défaut
                $journal = AccountingJournal::with('defaultAccount')->findOrFail($validated['journal_id']);

                $treasuryAccountId = $journal->default_account_id ?? $journal->defaultAccount?->id;

                // Fallback : recherche automatique d'un compte de trésorerie (571 pour caisse, 521 pour banque) si non configuré
                if (!$treasuryAccountId) {
                    $codePrefix = ($journal->type === 'bank') ? '521%' : '571%';
                    $fallbackAccount = DB::table('chart_of_accounts')->where('code', 'LIKE', $codePrefix)->first()
                        ?? DB::table('chart_of_accounts')->where('code', 'LIKE', '5%')->first();

                    if ($fallbackAccount) {
                        $treasuryAccountId = $fallbackAccount->id;
                    } else {
                        throw new \Exception("Le journal '{$journal->name}' n'a aucun compte comptable configuré et aucun compte de trésorerie (571/521) n'existe dans la table chart_of_accounts.");
                    }
                }

                // 2. Contrôle de l'existence du compte Tiers Client (411)
                $studentAccount = DB::table('chart_of_accounts')->where('code', 'LIKE', '411%')->first();
                if (!$studentAccount) {
                    throw new \Exception("Aucun compte client (411) n'a été trouvé dans le plan comptable (chart_of_accounts).");
                }

                $user = auth()->user();
                $receiptNumber = 'REC-' . date('Ym') . '-' . strtoupper(Str::random(5));
                $amountToDistribute = (float) $validated['amount'];

                // 3. Création du reçu de paiement
                $payment = Payment::create([
                    'school_id'               => $account->school_id,
                    'student_fee_account_id'  => $account->id,
                    'student_fee_schedule_id' => $validated['student_fee_schedule_id'],
                    'journal_id'              => $journal->id,
                    'receipt_number'          => $receiptNumber,
                    'amount'                  => $validated['amount'],
                    'payment_method'          => $validated['payment_method'],
                    'transaction_reference'   => $validated['transaction_reference'] ?? null,
                    'paid_at'                 => now(),
                    'received_by_id'          => $user->id ?? null,
                    'status'                  => 'completed',
                    'notes'                   => $validated['notes'] ?? null,
                ]);

                // 4. Distribution du versement sur les tranches d'échéancier
                $selectedScheduleId = $validated['student_fee_schedule_id'];
                $schedules = $account->schedules()
                    ->where('is_paid', false)
                    ->get()
                    ->sortBy(function ($schedule) use ($selectedScheduleId) {
                        return $schedule->id == $selectedScheduleId ? 0 : 1;
                    });

                foreach ($schedules as $schedule) {
                    if ($amountToDistribute <= 0) break;

                    $dueForThisSchedule = (float) ($schedule->amount - $schedule->paid_amount);
                    if ($dueForThisSchedule <= 0) continue;

                    $allocation = min($amountToDistribute, $dueForThisSchedule);

                    if ($allocation > 0) {
                        PaymentItem::create([
                            'payment_id'              => $payment->id,
                            'student_fee_schedule_id' => $schedule->id,
                            'amount_allocated'        => $allocation,
                        ]);

                        $schedule->paid_amount += $allocation;
                        $schedule->is_paid = (round($schedule->paid_amount, 2) >= round($schedule->amount, 2));
                        $schedule->save();

                        $amountToDistribute -= $allocation;
                    }
                }

                // 5. Mise à jour du compte scolarité
                $account->total_paid += $validated['amount'];
                $account->balance = max(0, ($account->total_due - $account->discount_amount) - $account->total_paid);
                $account->status = ($account->balance == 0) ? 'paid' : 'partially_paid';
                $account->save();

                // 6. Génération de l'écriture comptable
                $studentName = $account->student && $account->student->personne 
                    ? $account->student->personne->nom . ' ' . $account->student->personne->prenoms 
                    : 'Matricule ' . ($account->student->matricule ?? $account->id);

                $this->treasuryService->recordReceipt([
                    'journal_id'             => $journal->id,
                    'treasury_account_id'    => $treasuryAccountId,
                    'third_party_account_id' => $studentAccount->id,
                    'amount'                 => $validated['amount'],
                    'date'                   => now()->toDateString(),
                    'label'                  => 'Règlement scolarité - ' . $studentName,
                    'reference'              => $receiptNumber,
                ]);
            });

            return redirect()->back()->with('success', 'Encaissement et écriture comptable enregistrés avec succès.');

        } catch (\Exception $e) {
            Log::error("Erreur encaissement : " . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors de l\'encaissement : ' . $e->getMessage());
        }
    }

    public function cancel(Request $request, $paymentId)
    {
        $request->validate(['cancellation_reason' => 'required|string|max:255']);

        $payment = Payment::with(['items.schedule', 'account', 'journal'])->findOrFail($paymentId);

        if ($payment->status === 'cancelled') {
            return redirect()->back()->with('error', 'Paiement déjà annulé.');
        }

        try {
            DB::transaction(function () use ($payment, $request) {
                foreach ($payment->items as $item) {
                    $schedule = $item->schedule;
                    if ($schedule) {
                        $schedule->paid_amount = max(0, $schedule->paid_amount - $item->amount_allocated);
                        $schedule->is_paid = ($schedule->paid_amount >= $schedule->amount);
                        $schedule->save();
                    }
                }

                if ($payment->journal_id && $payment->journal) {
                    $studentAccount = DB::table('chart_of_accounts')->where('code', 'LIKE', '411%')->first();
                    
                    if (!$studentAccount) {
                        throw new \Exception("Aucun compte client (411) trouvé pour extourner l'écriture.");
                    }

                    $treasuryAccountId = $payment->journal->default_account_id;
                    if (!$treasuryAccountId) {
                        throw new \Exception("Compte de trésorerie manquant sur le journal.");
                    }

                    $this->treasuryService->reverseReceipt([
                        'journal_id'             => $payment->journal_id,
                        'treasury_account_id'    => $treasuryAccountId,
                        'third_party_account_id' => $studentAccount->id,
                        'amount'                 => $payment->amount,
                        'date'                   => now()->toDateString(),
                        'label'                  => 'Annulation encaissement N° ' . $payment->receipt_number,
                        'reference'              => 'ANNUL-' . $payment->receipt_number,
                    ]);
                }

                $payment->update([
                    'status'              => 'cancelled',
                    'cancellation_reason' => $request->cancellation_reason,
                    'cancelled_by_id'     => auth()->id(),
                    'cancelled_at'        => now(),
                ]);

                $account = $payment->account;
                $account->total_paid = max(0, $account->total_paid - $payment->amount);
                $account->balance = max(0, ($account->total_due - $account->discount_amount) - $account->total_paid);
                $account->status = ($account->total_paid <= 0) ? 'unpaid' : (($account->balance <= 0) ? 'paid' : 'partially_paid');
                $account->save();
            });

            return redirect()->back()->with('success', 'Paiement et écriture comptable associés annulés.');

        } catch (\Exception $e) {
            Log::error("Erreur annulation paiement : " . $e->getMessage());
            return redirect()->back()->with('error', 'Impossible d\'annuler le paiement : ' . $e->getMessage());
        }
    }

    public function updateAccount(Request $request, $id)
    {
        $request->validate([
            'total_due'          => 'required|numeric|min:0',
            'discount_amount'    => 'nullable|numeric|min:0',
            'target_fee_type'    => 'required|string',
        ]);

        $account = StudentFeeAccount::findOrFail($id);
        
        DB::transaction(function () use ($account, $request) {
            $account->total_due = $request->total_due;
            $discountAmount = (float) ($request->discount_amount ?? 0);
            $targetType = $request->input('target_fee_type');

            $this->applyDiscountToPlan($account, $targetType, $discountAmount);

            $totalDiscount = $account->schedules()->sum('discount_amount');
            $account->discount_amount = $totalDiscount;

            $account->balance = max(0, ($account->total_due - $account->discount_amount) - $account->total_paid);
            
            if ($account->balance <= 0 && $account->total_due > 0) {
                $account->status = 'paid';
            } elseif ($account->total_paid > 0) {
                $account->status = 'partially_paid';
            } else {
                $account->status = 'unpaid';
            }
            
            $account->save();
        });

        return redirect()->back()->with('success', 'La remise sur le plan sélectionné a été mise à jour.');
    }

    public function receiptPdf($paymentId)
    {
        $payment = Payment::with([
            'account.student.personne',
            'account.registration.schoolClass',
            'items.schedule',
            'cashier',
            'journal'
        ])->findOrFail($paymentId);

        $pdf = Pdf::loadView('school::accounting.receipt_pdf', compact('payment'));

        return $pdf->stream("recu_{$payment->receipt_number}.pdf");
    }

    public function accountStatementPdf($id)
    {
        $account = StudentFeeAccount::with([
            'student.personne',
            'registration.schoolClass',
            'schedules',
            'payments.cashier'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('school::accounting.account_statement_pdf', compact('account'));

        return $pdf->stream("situation_financiere_{$account->student->matricule}.pdf");
    }

    private function applyDiscountToPlan(StudentFeeAccount $account, string $targetType, float $discountAmount)
    {
        $schedules = $account->schedules()->get();

        $matchingSchedules = $schedules->filter(function ($schedule) use ($targetType) {
            $label = strtolower($schedule->label);
            if ($targetType === 'inscription') {
                return str_contains($label, 'inscription');
            } elseif ($targetType === 'scolarite') {
                return !str_contains($label, 'inscription');
            }
            return true;
        });

        $remainingDiscount = $discountAmount;

        foreach ($matchingSchedules as $schedule) {
            if (is_null($schedule->original_amount)) {
                $schedule->original_amount = $schedule->amount;
            }

            $baseAmount = (float) $schedule->original_amount;

            if ($remainingDiscount <= 0) {
                $schedule->discount_amount = 0;
                $schedule->amount = $baseAmount;
            } elseif ($remainingDiscount >= $baseAmount) {
                $schedule->discount_amount = $baseAmount;
                $schedule->amount = 0;
                $remainingDiscount -= $baseAmount;
            } else {
                $schedule->discount_amount = $remainingDiscount;
                $schedule->amount = $baseAmount - $remainingDiscount;
                $remainingDiscount = 0;
            }

            $schedule->is_paid = ($schedule->amount == 0 || $schedule->paid_amount >= $schedule->amount);
            $schedule->save();
        }
    }

    public function addExtraFee(Request $request, $id)
    {
        $request->validate([
            'fee_type_preset' => 'required|string',
            'custom_label'    => 'nullable|string|max:150',
            'amount'          => 'required|numeric|min:100',
            'due_date'        => 'required|date',
            'is_blocking'     => 'nullable|boolean',
        ]);

        $account = StudentFeeAccount::findOrFail($id);

        DB::transaction(function () use ($account, $request) {
            $label = ($request->fee_type_preset === 'custom') 
                ? $request->custom_label 
                : $request->fee_type_preset;

            $amount = (float) $request->amount;

            StudentFeeSchedule::create([
                'student_fee_account_id' => $account->id,
                'fee_plan_item_id'       => null,
                'label'                  => $label,
                'amount'                 => $amount,
                'original_amount'        => $amount,
                'discount_amount'        => 0,
                'paid_amount'            => 0,
                'due_date'               => $request->due_date,
                'is_paid'                => false,
                'is_blocking'            => $request->has('is_blocking'),
            ]);

            $account->total_due += $amount;
            $account->balance = max(0, ($account->total_due - $account->discount_amount) - $account->total_paid);

            if ($account->balance > 0 && $account->status === 'paid') {
                $account->status = 'partially_paid';
            }

            $account->save();
        });

        return redirect()->back()->with('success', 'Frais additionnels ajoutés à l\'échéancier avec succès.');
    }
}