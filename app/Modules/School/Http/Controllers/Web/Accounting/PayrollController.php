<?php

namespace App\Modules\School\Http\Controllers\Web\Accounting;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\Staff;
use App\Modules\School\Models\Payroll;
use App\Modules\School\Models\School;
use App\Modules\School\Services\PayrollService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PayrollController extends Controller
{
    protected PayrollService $payrollService;

    public function __construct(PayrollService $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    /**
     * Liste des fiches de paie générées
     */
    public function index(Request $request): View
    {
        $schoolId = $request->input('school_id', auth()->user()->school_id ?? 1);

        $query = Payroll::with(['staff.personne', 'staff.contracts'])
            ->where('school_id', $schoolId);

        if ($request->filled('staff_id')) {
            $query->where('staff_id', $request->staff_id);
        }

        if ($request->filled('period_start')) {
            $query->where('period_start', '>=', $request->period_start);
        }

        $payrolls = $query->orderBy('created_at', 'desc')->paginate(15);

        $teachers = Staff::whereHas('contracts', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->with('personne')
            ->get();

        $schools = School::orderBy('name', 'asc')->get();

        return view('school::payroll.index', compact('payrolls', 'teachers', 'schools', 'schoolId'));
    }

    /**
     * Formulaire AJAX de création de paie (Injecté dans le Modal)
     */
    public function create(Request $request): View
    {
        $schoolId = $request->input('school_id', auth()->user()->school_id ?? 1);

        $schools = School::orderBy('name', 'asc')->get();

        $staffs = Staff::whereHas('contracts', function ($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->with('personne')
            ->get();

        return view('school::payroll.create', compact('staffs', 'schools', 'schoolId'));
    }

    /**
     * Génère la paie d'un employé (Permanent ou Vacataire) avec prise en compte des éléments variables
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'staff_id'            => 'required|exists:staff,id',
            'period_start'        => 'required|date',
            'period_end'          => 'required|date|after_or_equal:period_start',
            'transport_allowance' => 'nullable|numeric|min:0',
            'extra_bonuses'       => 'nullable|numeric|min:0',
            'advance_deduction'   => 'nullable|numeric|min:0',
        ]);

        try {
            $staff = Staff::findOrFail($validated['staff_id']);

            // Extraction des variables optionnelles
            $extraInputs = [
                'transport_allowance' => $validated['transport_allowance'] ?? 0,
                'extra_bonuses'       => $validated['extra_bonuses'] ?? 0,
                'advance_deduction'   => $validated['advance_deduction'] ?? 0,
            ];

            // Appel au service avec les variables du mois en 5ème argument
            $this->payrollService->generatePayroll(
                $staff,
                $validated['period_start'],
                $validated['period_end'],
                auth()->id() ?? 1,
                $extraInputs
            );

            return redirect()->route('school.accounting.payrolls.index')
                ->with('success', 'La fiche de paie complète a été calculée et générée avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('school.accounting.payrolls.index')
                ->with('error', 'Erreur de calcul : ' . $e->getMessage());
        }
    }

    /**
     * Affiche les détails d'une fiche de paie dans le Modal AJAX (avec rubriques/items)
     */
    public function show(int $id): View
    {
        $payroll = Payroll::with([
            'staff.personne', 
            'staff.contracts.role', 
            'items'
        ])->findOrFail($id);

        return view('school::payroll.show', compact('payroll'));
    }

    /**
     * Supprime une fiche de paie
     */
    public function destroy(int $id): RedirectResponse
    {
        $payroll = Payroll::findOrFail($id);
        $payroll->delete();

        return redirect()->route('school.accounting.payrolls.index')
            ->with('success', 'La fiche de paie a été supprimée avec succès.');
    }

    /**
     * Génère et télécharge le bulletin de paie au format PDF (avec rubriques/items).
     */
    public function downloadPdf(int $id)
    {
        $payroll = Payroll::with([
            'staff.personne',
            'staff.contracts',
            'school',
            'items'
        ])->findOrFail($id);

        $pdf = Pdf::loadView('school::payroll.pdfs.paystub', compact('payroll'))
            ->setPaper('a4', 'portrait');

        $fileName = 'Bulletin_' . Str::slug($payroll->payroll_number) . '.pdf';

        return $pdf->download($fileName);
    }
}