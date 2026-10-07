<?php

namespace App\Modules\School\Http\Controllers\Web\Academique;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\School;
use App\Modules\School\Models\SchoolSubject;
use App\Modules\School\Models\Subject;
use App\Modules\School\Models\TeachingUnit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SchoolSubjectController extends Controller
{
    public function index(Request $request): View
    {
        $query = SchoolSubject::with(['school', 'subject', 'teachingUnit']);

        if ($request->filled('school_id')) {
            $query->where('school_id', $request->input('school_id'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('custom_name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhereHas('subject', function ($subQ) use ($search) {
                      $subQ->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $schoolSubjects = $query->latest()->paginate(15)->withQueryString();
        $schools = School::orderBy('name')->get();

        return view('School::academique.school_subjects.index', compact('schoolSubjects', 'schools'));
    }

    public function create(Request $request)
    {
        $schools = School::orderBy('name')->get();
        
        $subjects = Subject::where('is_active', true)->select('id', 'name', 'code')->orderBy('name')->get();

        $selectedSchoolId = $request->query('school_id');
        $teachingUnits = $selectedSchoolId 
            ? TeachingUnit::where('school_id', $selectedSchoolId)->get() 
            : collect();

        $viewData = compact('schools', 'subjects', 'teachingUnits', 'selectedSchoolId');

        if ($request->ajax()) {
            return view('School::academique.school_subjects.create', $viewData);
        }

        return view('School::academique.school_subjects.create', $viewData);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_id'        => 'required|exists:schools,id',
            'subject_id'       => 'required|exists:subjects,id',
            'teaching_unit_id' => 'nullable|exists:teaching_units,id',
            'custom_name'      => 'nullable|string|max:255',
            'code'             => 'nullable|string|max:50',
            'color_code'       => 'nullable|string|max:7',
            'credits'          => 'required|numeric|min:0',
            'coefficient'      => 'required|numeric|min:0',
            'hours_cm'         => 'required|integer|min:0',
            'hours_td'         => 'required|integer|min:0',
            'hours_tp'         => 'required|integer|min:0',
            'is_active'        => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $exists = SchoolSubject::where('school_id', $validated['school_id'])
            ->where('subject_id', $validated['subject_id'])
            ->where('teaching_unit_id', $validated['teaching_unit_id'] ?? null)
            ->exists();

        if ($exists) {
            return back()
                ->withInput()
                ->withErrors(['subject_id' => 'Cette matière est déjà configurée pour cette école / unité d\'enseignement.']);
        }

        SchoolSubject::create($validated);

        return redirect()
            ->route('academic.school-subjects.index', ['school_id' => $validated['school_id']])
            ->with('success', 'Matière configurée pour l\'établissement avec succès.');
    }

    public function show(Request $request, SchoolSubject $schoolSubject)
    {
        $schoolSubject->load(['school', 'subject', 'teachingUnit', 'schedules']);

        if ($request->ajax()) {
            return view('School::academique.school_subjects.show', compact('schoolSubject'));
        }

        return view('School::academique.school_subjects.show', compact('schoolSubject'));
    }

    public function edit(Request $request, SchoolSubject $schoolSubject)
    {
        $subjects = Subject::where('is_active', true)->orderBy('name')->get();
        $teachingUnits = TeachingUnit::where('school_id', $schoolSubject->school_id)->get();

        $viewData = compact('schoolSubject', 'subjects', 'teachingUnits');

        if ($request->ajax()) {
            return view('School::academique.school_subjects.edit', $viewData);
        }

        return view('School::academique.school_subjects.edit', $viewData);
    }

    public function update(Request $request, SchoolSubject $schoolSubject): RedirectResponse
    {
        $validated = $request->validate([
            'teaching_unit_id' => 'nullable|exists:teaching_units,id',
            'custom_name'      => 'nullable|string|max:255',
            'code'             => 'nullable|string|max:50',
            'color_code'       => 'nullable|string|max:7',
            'credits'          => 'required|numeric|min:0',
            'coefficient'      => 'required|numeric|min:0',
            'hours_cm'         => 'required|integer|min:0',
            'hours_td'         => 'required|integer|min:0',
            'hours_tp'         => 'required|integer|min:0',
            'is_active'        => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $schoolSubject->update($validated);

        return redirect()
            ->route('academic.school-subjects.index', ['school_id' => $schoolSubject->school_id])
            ->with('success', 'Configuration de la matière mise à jour.');
    }

    public function destroy(SchoolSubject $schoolSubject): RedirectResponse
    {
        if ($schoolSubject->schedules()->exists()) {
            return back()->with('error', 'Impossible de retirer cette matière car des séances d\'emploi du temps y sont associées.');
        }

        $schoolId = $schoolSubject->school_id;
        $schoolSubject->delete();

        return redirect()
            ->route('academic.school-subjects.index', ['school_id' => $schoolId])
            ->with('success', 'Matière retirée de l\'établissement.');
    }

    /**
     * API Endpoint pour charger dynamiquement les UE par Établissement
     */
    public function getTeachingUnitsBySchool(Request $request): JsonResponse
    {
        $units = TeachingUnit::where('school_id', $request->query('school_id'))
            ->select('id', 'name', 'code')
            ->get();

        return response()->json($units);
    }
}