<?php

namespace App\Modules\School\Http\Controllers\Web\Evaluation;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\AcademicPeriod;
use App\Modules\School\Models\Evaluation;
use App\Modules\School\Models\EvaluationType;
use App\Modules\School\Models\School;
use App\Modules\School\Models\SchoolClass;
use App\Modules\School\Models\SchoolSubject;
use App\Modules\School\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class EvaluationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Vérification des rôles (ex: Spatie Permission ou logique personnalisée)
        $isTeacher = $user->hasRole('teacher') || $user->hasRole('enseignant');
        
        // Privilège pour modifier/supprimer/dépublier même si c'est publié
        $canManagePublished = $user->hasRole('super-admin') 
            || $user->hasRole('admin-ecole') 
            || $user->hasRole('directeur-etudes') 
            || !$isTeacher;

        $schoolId = $request->input('school_id') ?? $user->school_id;

        $evaluations = Evaluation::with(['schoolClass', 'subject.teachingUnit', 'type', 'academicPeriod'])
            ->when($schoolId, fn($q) => $q->where('school_id', $schoolId))
            ->when($request->school_class_id, fn($q) => $q->where('school_class_id', $request->school_class_id))
            ->when($request->academic_period_id, fn($q) => $q->where('academic_period_id', $request->academic_period_id))
            ->latest()
            ->paginate(15);

        $formData = $this->getFormData($request, $schoolId);

        return view('School::evaluation.evaluations.index', array_merge(
            compact('evaluations', 'canManagePublished', 'isTeacher'), 
            $formData
        ));
    }

    public function create(Request $request)
    {
        $formData = $this->getFormData($request);

        return view('School::evaluation.evaluations.create', $formData);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'school_id'          => 'required|exists:schools,id',
            'school_class_id'    => 'required|exists:school_classes,id',
            'school_subject_id'  => 'required|exists:school_subjects,id',
            'evaluation_type_id' => 'required|exists:evaluation_types,id',
            'academic_period_id' => 'nullable|exists:academic_periods,id',
            'title'              => 'required|string|max:150',
            'coefficient'        => 'required|numeric|min:0.1',
            'max_score'          => 'required|numeric|min:1',
            'evaluated_at'       => 'nullable|date',
            'is_published'       => 'nullable|boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');

        $evaluation = Evaluation::create($validated);

        return redirect()->route('evaluation.evaluations.index', $evaluation->id)
            ->with('success', __('Évaluation créée. Vous pouvez procéder à la saisie des notes.'));
    }

    public function show(Evaluation $evaluation)
    {
        $user = Auth::user();

        // Vérification si l'utilisateur est un enseignant
        $isTeacher = $user->hasRole('teacher') || $user->hasRole('enseignant');

        // Privilège pour gérer/saisir les notes même si l'évaluation est publiée (Admin / Directeur des études)
        $canManagePublished = $user->hasRole('super-admin') 
            || $user->hasRole('admin-ecole') 
            || $user->hasRole('directeur-etudes') 
            || !$isTeacher;

        // L'utilisateur peut saisir/modifier les notes si l'évaluation N'EST PAS publiée OU s'il a les privilèges admin
        $canEditGrades = !$evaluation->is_published || $canManagePublished;

        $evaluation->load([
            'schoolClass', 
            'subject.teachingUnit', 
            'type', 
            'grades.registration.student.personne'
        ]);        

        $registrations = Registration::with('student.personne')
            ->where('school_class_id', $evaluation->school_class_id)
            ->get();

        $existingGrades = $evaluation->grades->keyBy('registration_id');

        return view('School::evaluation.evaluations.show', compact('evaluation', 'registrations', 'existingGrades', 'canEditGrades'));
    }

    public function edit(Evaluation $evaluation)
    {
        $user = Auth::user();
        $isTeacher = $user->hasRole('teacher') || $user->hasRole('enseignant');
        $isAdmin = !$isTeacher;

        $schools = $isTeacher 
            ? DB::table('schedules')
                ->join('schools', 'schedules.school_id', '=', 'schools.id')
                ->where('schedules.staff_id', $user->personne_id ?? $user->id)
                ->whereNull('schedules.deleted_at')
                ->select('schools.id', 'schools.name')
                ->distinct()
                ->orderBy('schools.name')
                ->get()
            : School::orderBy('name')->get();

        $classes = SchoolClass::where('school_id', $evaluation->school_id)->get();
        $subjects = SchoolSubject::where('school_id', $evaluation->school_id)->get();
        $evaluationTypes = EvaluationType::all();
        $academicPeriods = AcademicPeriod::where('school_id', $evaluation->school_id)->get();

        return view('School::evaluation.evaluations.edit', compact(
            'evaluation',
            'isAdmin',
            'isTeacher',
            'schools',
            'classes',
            'subjects',
            'evaluationTypes',
            'academicPeriods'
        ));
    }

    public function update(Request $request, Evaluation $evaluation)
    {
        $validated = $request->validate([
            'school_id'          => 'required|exists:schools,id',
            'school_class_id'    => 'required|exists:school_classes,id',
            'school_subject_id'  => 'required|exists:school_subjects,id',
            'evaluation_type_id' => 'required|exists:evaluation_types,id',
            'academic_period_id' => 'nullable|exists:academic_periods,id',
            'title'              => 'required|string|max:150',
            'coefficient'        => 'required|numeric|min:0.1',
            'max_score'          => 'required|numeric|min:1',
            'evaluated_at'       => 'nullable|date',
            'is_published'       => 'nullable|boolean',
        ]);

        $validated['is_published'] = $request->has('is_published');

        $evaluation->update($validated);

        return redirect()->back()->with('success', __('Évaluation mise à jour.'));
    }

    public function togglePublish(Evaluation $evaluation)
    {
        $evaluation->update(['is_published' => !$evaluation->is_published]);

        return redirect()->back()->with('success', $evaluation->is_published ? __('Notes publiées.') : __('Notes masquées.'));
    }

    public function printPdf(Evaluation $evaluation)
    {
        $evaluation->load([
            'schoolClass', 
            'subject.teachingUnit', 
            'type', 
            'grades.registration.student.personne'
        ]);

        $registrations = Registration::with('student.personne')
            ->where('school_class_id', $evaluation->school_class_id)
            ->get()
            ->sortBy(function ($registration) {
                return $registration->student->personne->nom ?? '';
            });

        $existingGrades = $evaluation->grades->keyBy('registration_id');

        $pdf = Pdf::loadView('School::evaluation.evaluations.pdf', compact('evaluation', 'registrations', 'existingGrades'))
                ->setPaper('a4', 'portrait');

        return $pdf->stream('Fiche_de_notes_' . Str::slug($evaluation->title) . '.pdf');
    }

    public function destroy(Evaluation $evaluation)
    {
        $evaluation->delete();
        return redirect()->route('evaluation.evaluations.index')->with('success', __('Évaluation supprimée.'));
    }

    /**
     * Endpoint AJAX : Charge la liste des classes selon l'établissement et le profil utilisateur
     */
    public function getClassesBySchool(Request $request)
    {
        $schoolId = $request->input('school_id');
        $user = Auth::user();
        $isTeacher = $user->hasRole('teacher') || $user->hasRole('enseignant');

        if ($isTeacher) {
            $staffId = $user->personne_id ?? $user->id;
            $classes = DB::table('schedules')
                ->join('school_classes', 'schedules.school_class_id', '=', 'school_classes.id')
                ->where('schedules.staff_id', $staffId)
                ->where('schedules.school_id', $schoolId)
                ->whereNull('schedules.deleted_at')
                ->select('school_classes.id', 'school_classes.name')
                ->distinct()
                ->orderBy('school_classes.name')
                ->get();
        } else {
            $classes = SchoolClass::where('school_id', $schoolId)
                ->select('id', 'name')
                ->orderBy('name')
                ->get();
        }

        return response()->json($classes);
    }

    /**
     * Endpoint AJAX : Charge la liste des matières selon l'établissement, la classe et le profil
     */
    public function getSubjectsByClass(Request $request)
    {
        $schoolId = $request->input('school_id');
        $classId = $request->input('school_class_id');
        $user = Auth::user();
        $isTeacher = $user->hasRole('teacher') || $user->hasRole('enseignant');

        if ($isTeacher) {
            $staffId = $user->personne_id ?? $user->id;
            $subjects = DB::table('schedules')
                ->join('school_subjects', 'schedules.school_subject_id', '=', 'school_subjects.id')
                ->join('subjects', 'school_subjects.subject_id', '=', 'subjects.id')
                ->where('schedules.staff_id', $staffId)
                ->where('schedules.school_id', $schoolId)
                ->where('schedules.school_class_id', $classId)
                ->whereNull('schedules.deleted_at')
                ->select('school_subjects.id', 'subjects.name as name')
                ->distinct()
                ->orderBy('subjects.name')
                ->get();
        } else {
            $subjects = DB::table('school_subjects')
                ->join('subjects', 'school_subjects.subject_id', '=', 'subjects.id')
                ->where('school_subjects.school_id', $schoolId)
                ->whereNull('school_subjects.deleted_at')
                ->select('school_subjects.id', 'subjects.name as name')
                ->distinct()
                ->orderBy('subjects.name')
                ->get();
        }

        return response()->json($subjects);
    }

    /**
     * Préparation des données initiales du formulaire
     */
    private function getFormData(Request $request, ?int $defaultSchoolId = null): array
    {
        $user = Auth::user();
        $isTeacher = $user->hasRole('teacher') || $user->hasRole('enseignant');
        $isAdmin = !$isTeacher;

        if ($isTeacher) {
            $staffId = $user->personne_id ?? $user->id;
            
            // Liste des écoles associées aux heures de cours de l'enseignant
            $schools = DB::table('schedules')
                ->join('schools', 'schedules.school_id', '=', 'schools.id')
                ->where('schedules.staff_id', $staffId)
                ->whereNull('schedules.deleted_at')
                ->select('schools.id', 'schools.name')
                ->distinct()
                ->orderBy('schools.name')
                ->get();
        } else {
            // Liste de toutes les écoles du tenant
            $schools = School::orderBy('name')->get();
        }

        $selectedSchoolId = $request->input('school_id', $defaultSchoolId ?? $schools->first()?->id);

        $evaluationTypes = $selectedSchoolId 
            ? EvaluationType::where('school_id', $selectedSchoolId)->get() 
            : EvaluationType::all();

        $academicPeriods = $selectedSchoolId 
            ? AcademicPeriod::where('school_id', $selectedSchoolId)->get() 
            : AcademicPeriod::all();

        // Les classes et matières sont chargées dynamiquement via AJAX selon le choix de l'école/classe
        $classes = collect();
        $subjects = collect();

        return compact('schools', 'classes', 'subjects', 'evaluationTypes', 'academicPeriods', 'isAdmin', 'isTeacher');
    }
}