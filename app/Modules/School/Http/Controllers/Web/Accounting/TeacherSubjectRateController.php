<?php

namespace App\Modules\School\Http\Controllers\Web\Accounting;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\Staff;
use App\Modules\School\Models\SchoolClass;
use App\Modules\School\Models\School;
use App\Modules\School\Models\TeacherSubjectRate;
use App\Modules\School\Models\HourlyRate;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TeacherSubjectRateController extends Controller
{
    /**
     * Liste des cours attribués avec leurs taux horaires et volumes respectifs
     */
    public function index(Request $request): View
    {
        $schoolId = $request->input('school_id', auth()->user()->school_id ?? 1);

        $query = TeacherSubjectRate::with(['staff.personne', 'schoolClass', 'subject', 'materials'])
            ->where('school_id', $schoolId);

        // 1. Filtre par enseignant
        if ($request->filled('staff_id')) {
            $query->where('staff_id', $request->staff_id);
        }

        // 2. Filtre par classe
        if ($request->filled('class_id')) {
            $query->where('school_class_id', $request->class_id);
        }

        $subjectRates = $query->paginate(15);

        $schools = School::all();

        $teachers = Staff::whereHas('contracts', function ($q) use ($schoolId) {
            $q->where('school_id', $schoolId);
        })
        ->with('personne')
        ->get();

        $classesQuery = SchoolClass::where('school_id', $schoolId);

        if ($request->filled('staff_id')) {
            $classesQuery->whereHas('schedules', function ($q) use ($request) {
                $q->where('staff_id', $request->staff_id);
            });
        }

        $classes = $classesQuery->get();

        return view('School::payroll.rates.index', compact(
            'subjectRates', 
            'schools', 
            'teachers', 
            'classes', 
            'schoolId'
        ));
    }

    /**
     * Affiche la fiche de détail (Modal AJAX)
     */
    public function show(int $id)
    {
        $subjectRate = TeacherSubjectRate::with([
            'staff.personne',
            'staff.degree',
            'schoolClass',
            'subject.subject',
            'materials'
        ])->findOrFail($id);

        return view('School::payroll.rates.show', compact('subjectRate'));
    }

    /**
     * Affiche le formulaire d'édition (Modal AJAX)
     */
    public function edit(int $id)
    {
        $subjectRate = TeacherSubjectRate::with([
            'staff.personne',
            'schoolClass',
            'subject.subject'
        ])->findOrFail($id);

        return view('School::payroll.rates.edit', compact('subjectRate'));
    }

    /**
     * Met à jour les taux et les volumes horaires négociés pour un cours donné
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'rate_cm'       => 'required|numeric|min:0',
            'rate_td'       => 'required|numeric|min:0',
            'rate_tp'       => 'required|numeric|min:0',
            'rate_examen'   => 'required|numeric|min:0',
            'volume_cm'     => 'nullable|integer|min:0',
            'volume_td'     => 'nullable|integer|min:0',
            'volume_tp'     => 'nullable|integer|min:0',
            'volume_examen' => 'nullable|integer|min:0',
        ]);

        $subjectRate = TeacherSubjectRate::findOrFail($id);

        $subjectRate->update([
            'rate_cm'       => $validated['rate_cm'],
            'rate_td'       => $validated['rate_td'],
            'rate_tp'       => $validated['rate_tp'],
            'rate_examen'   => $validated['rate_examen'],
            'volume_cm'     => $validated['volume_cm'] ?? 0,
            'volume_td'     => $validated['volume_td'] ?? 0,
            'volume_tp'     => $validated['volume_tp'] ?? 0,
            'volume_examen' => $validated['volume_examen'] ?? 0,
            'is_customized' => true,
        ]);

        return redirect()->route('school.accounting.teacher-rates.index')
                ->with('success', 'Les taux et volumes horaires ont été mis à jour avec succès.');
    }

    /**
     * Réinitialise le tarif selon la grille par défaut
     */
    public function resetToDefault(int $id): RedirectResponse
    {
        $subjectRate = TeacherSubjectRate::with(['staff', 'schoolClass'])->findOrFail($id);
        $staff = $subjectRate->staff;
        $class = $subjectRate->schoolClass;

        $defaultGrid = HourlyRate::where('school_id', $staff->school_id)
            ->where('degree_id', $staff->degree_id)
            ->where('level_id', $class->level_id)
            ->where(function ($q) use ($class) {
                $q->where('series_id', $class->series_id)->orWhereNull('series_id');
            })
            ->first();

        if (!$defaultGrid) {
            $defaultGrid = HourlyRate::where('school_id', $staff->school_id)
                ->where('degree_id', $staff->degree_id)
                ->whereNull('level_id')
                ->first();
        }

        $subjectRate->update([
            'rate_cm'       => $defaultGrid->rate_cm ?? 0,
            'rate_td'       => $defaultGrid->rate_td ?? 0,
            'rate_tp'       => $defaultGrid->rate_tp ?? 0,
            'rate_examen'   => $defaultGrid->rate_examen ?? 0,
            'is_customized' => false,
        ]);

        return redirect()->route('school.accounting.teacher-rates.index')
            ->with('success', 'Le tarif a été réinitialisé selon la grille par défaut.');
    }
}