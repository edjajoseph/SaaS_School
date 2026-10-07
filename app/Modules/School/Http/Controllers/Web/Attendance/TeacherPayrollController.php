<?php

namespace App\Modules\School\Http\Controllers\Web\Attendance;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\Staff;
use App\Modules\School\Services\StaffAttendancePayrollService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\TeacherPayslipMail;
use Illuminate\Support\Facades\Mail;

class TeacherPayrollController extends Controller
{
    protected StaffAttendancePayrollService $payrollService;

    public function __construct(StaffAttendancePayrollService $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    /**
     * Tableau de bord de la paie des vacataires
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $staffId = $request->input('staff_id');

        $summary = $this->payrollService->getPayrollSummary($startDate, $endDate, $staffId);
        $teachers = Staff::with('personne')->get();

        // Calcul des totaux généraux pour l'en-tête du tableau de bord
        $grandTotalHours = array_sum(array_column($summary, 'total_hours'));
        $grandTotalNet = array_sum(array_column($summary, 'net_total'));
        $grandTotalPenalties = array_sum(array_column($summary, 'penalties_total'));

        return view('School::staff_attendance.payroll_dashboard', compact(
            'summary',
            'teachers',
            'startDate',
            'endDate',
            'staffId',
            'grandTotalHours',
            'grandTotalNet',
            'grandTotalPenalties'
        ));
    }

    /**
     * Clôturer et enregistrer les bulletins de paie pour l'ensemble du personnel
     */
    public function generateBulkPayroll(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $staffList = Staff::all();

        $count = 0;
        foreach ($staffList as $staff) {
            $this->payrollService->generateMonthlyPayroll(
                $staff, 
                $startDate, 
                $endDate, 
                auth()->id()
            );
            $count++;
        }

    return redirect()->back()->with('success', "Génération réussie de {$count} bulletin(s) de paie.");
}

    /**
     * Exportation au format PDF de la fiche récapitulative de paie
     */
    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $staffId = $request->input('staff_id');

        $summary = $this->payrollService->getPayrollSummary($startDate, $endDate, $staffId);

        $pdf = Pdf::loadView('School::staff_attendance.exports.payroll_pdf', [
            'summary'   => $summary,
            'startDate' => $startDate,
            'endDate'   => $endDate,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('etat_emargement_paie_' . $startDate . '_au_' . $endDate . '.pdf');
    }

    /**
     * Exportation CSV/Excel des émargements et déductions
     */
    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $staffId = $request->input('staff_id');

        $summary = $this->payrollService->getPayrollSummary($startDate, $endDate, $staffId);

        $fileName = 'etat_paie_vacataires_' . date('Y_m_d_His') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($summary) {
            $file = fopen('php://output', 'w');
            // En-tête du fichier CSV
            fputcsv($file, [
                'Matricule',
                'Enseignant',
                'Nombre de Seances',
                'Heures Effectives',
                'Retard Total (Min)',
                'Montant Brut (FCFA)',
                'Penalites/Retard (FCFA)',
                'Net a Payer (FCFA)'
            ], ';');

            foreach ($summary as $item) {
                $nomComplet = ($item['staff']->personne->nom ?? '') . ' ' . ($item['staff']->personne->prenoms ?? '');
                fputcsv($file, [
                    $item['staff']->registration_number ?? 'N/A',
                    $nomComplet,
                    $item['total_sessions'],
                    $item['total_hours'],
                    $item['total_late_min'],
                    $item['gross_total'],
                    $item['penalties_total'],
                    $item['net_total'],
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Génère la fiche de paie individuelle PDF pour un enseignant spécifique.
     */
    public function exportIndividualPdf(Request $request, int $staffId)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Récupération des données uniquement pour cet enseignant
        $summary = $this->payrollService->getPayrollSummary($startDate, $endDate, $staffId);

        if (empty($summary) || !isset($summary[$staffId])) {
            return redirect()->back()->with('error', 'Aucune donnée de pointage trouvée pour cet enseignant sur la période sélectionnée.');
        }

        $payrollData = $summary[$staffId];

        $pdf = Pdf::loadView('School::staff_attendance.exports.teacher_payslip_pdf', [
            'staff'       => $payrollData['staff'],
            'details'     => $payrollData['details'],
            'totalHours'  => $payrollData['total_hours'],
            'totalLate'   => $payrollData['total_late_min'],
            'grossTotal'  => $payrollData['gross_total'],
            'penalties'   => $payrollData['penalties_total'],
            'netTotal'    => $payrollData['net_total'],
            'startDate'   => $startDate,
            'endDate'     => $endDate,
        ])->setPaper('a4', 'portrait');

        $nomEnseignant = str_replace(' ', '_', $payrollData['staff']->personne->nom ?? 'enseignant');
        return $pdf->download('fiche_de_paie_' . $nomEnseignant . '_' . $startDate . '_au_' . $endDate . '.pdf');
    }

    /**
     * Envoie la fiche de paie individuelle par e-mail à l'enseignant.
     */
    public function sendEmail(Request $request, int $staffId)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $summary = $this->payrollService->getPayrollSummary($startDate, $endDate, $staffId);

        if (empty($summary) || !isset($summary[$staffId])) {
            return redirect()->back()->with('error', 'Aucune donnée de pointage disponible pour cet enseignant.');
        }

        $payrollData = $summary[$staffId];
        $staff = $payrollData['staff'];

        // Adresse e-mail issue du compte utilisateur ou du profil personne
        $recipientEmail = $staff->user->email ?? $staff->personne->email ?? null;

        if (!$recipientEmail) {
            return redirect()->back()->with('error', 'Aucune adresse e-mail configurée pour cet enseignant.');
        }

        try {
            Mail::to($recipientEmail)->send(new TeacherPayslipMail($staff, $payrollData, $startDate, $endDate));
            return redirect()->back()->with('success', 'La fiche de paie a été envoyée par e-mail à ' . $recipientEmail);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'envoi de l\'e-mail : ' . $e->getMessage());
        }
    }
}