<?php

namespace App\Modules\School\Http\Controllers\Web\Accounting;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\AcademicYear;
use App\Modules\School\Models\FiscalYear;
use Illuminate\Http\Request;

class FiscalYearController extends Controller
{
    public function index()
    {
        $fiscalYears = FiscalYear::with('academicYear')
            ->orderBy('start_date', 'desc')
            ->paginate(10);

        return view('school::accounting.fiscal_years.index', compact('fiscalYears'));
    }

    public function create()
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        return view('school::accounting.fiscal_years.create', compact('academicYears'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name'             => 'nullable|string|max:255',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after:start_date',
            'status'           => 'required|in:open,closed',
        ]);

        // Si le nom est laissé vide, on peut réutiliser le nom de l'année académique
        if (empty($validated['name'])) {
            $academicYear = AcademicYear::findOrFail($validated['academic_year_id']);
            $validated['name'] = 'Exercice ' . $academicYear->name;
        }

        FiscalYear::create($validated);

        return redirect()->route('school.accounting.fiscal-years.index')
            ->with('success', 'Exercice comptable créé avec succès.');
    }

    public function edit(FiscalYear $fiscalYear)
    {
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();
        return view('school::accounting.fiscal_years.edit', compact('fiscalYear', 'academicYears'));
    }

    public function update(Request $request, FiscalYear $fiscalYear)
    {
        $validated = $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
            'name'             => 'nullable|string|max:255',
            'start_date'       => 'required|date',
            'end_date'         => 'required|date|after:start_date',
            'status'           => 'required|in:open,closed',
        ]);

        if (empty($validated['name'])) {
            $academicYear = AcademicYear::findOrFail($validated['academic_year_id']);
            $validated['name'] = 'Exercice ' . $academicYear->name;
        }

        $fiscalYear->update($validated);

        return redirect()->route('school.accounting.fiscal-years.index')
            ->with('success', 'Exercice comptable mis à jour.');
    }

    public function destroy(FiscalYear $fiscalYear)
    {
        $fiscalYear->delete();
        return redirect()->route('school.accounting.fiscal-years.index')
            ->with('success', 'Exercice comptable supprimé.');
    }
}