<?php

namespace App\Modules\School\Http\Controllers\Web\Evaluation;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\SchoolClass;
use App\Modules\School\Models\SchoolSubject;
use App\Modules\School\Models\AcademicPeriod;
use App\Modules\School\Models\SubjectClassAverage;
use App\Modules\School\Services\GradingService;
use App\Modules\School\Models\School;
use App\Modules\School\Models\Schedule;
use Illuminate\Http\Request;

class TeacherGradeController extends Controller
{
    /**
     * Formulaire de sélection et affichage du PV de l'ECUE.
     */
    public function index(Request $request)
    {
        // Récupération de tous les établissements
        $schools = School::all();

        // Récupération sécurisée des paramètres de filtrage
        $selectedSchoolId = $request->filled('school_id') ? $request->input('school_id') : null;
        $selectedClassId  = $request->filled('school_class_id') ? $request->input('school_class_id') : null;
        $selectedSubjectId = $request->filled('school_subject_id') ? $request->input('school_subject_id') : null;
        $selectedPeriodId  = $request->filled('academic_period_id') ? $request->input('academic_period_id') : null;

        // Chargement conditionnel selon qu'un établissement est sélectionné ou non
        if ($selectedSchoolId) {
            $classes = SchoolClass::where('school_id', $selectedSchoolId)->get();
            $periods = AcademicPeriod::where('school_id', $selectedSchoolId)->get();
        } else {
            $classes = SchoolClass::all();
            $periods = AcademicPeriod::all();
        }

        $subjects = SchoolSubject::all();
        $classAverage = null;

        // Récupération des données uniquement si la requête est complète
        if ($selectedSchoolId && $selectedClassId && $selectedSubjectId && $selectedPeriodId) {
            $classAverage = SubjectClassAverage::with([
                'studentAverages.registration.student',
                'schoolClass',
                'subject'
            ])
            ->where('school_id', $selectedSchoolId)
            ->where('school_class_id', $selectedClassId)
            ->where('school_subject_id', $selectedSubjectId)
            ->where('academic_period_id', $selectedPeriodId)
            ->first();
        }

        return view('school::evaluation.grades.subject-summary', compact(
            'schools',
            'classes',
            'subjects',
            'periods',
            'selectedSchoolId',
            'selectedClassId',
            'selectedSubjectId',
            'selectedPeriodId',
            'classAverage'
        ));
    }

    /**
     * Calculer et archiver les moyennes et rangs.
     */
    public function calculateAndArchive(Request $request)
    {
        $validated = $request->validate([
            'school_id'          => 'required|exists:schools,id',
            'school_class_id'    => 'required|exists:school_classes,id',
            'school_subject_id'  => 'required|exists:school_subjects,id',
            'academic_period_id' => 'required|exists:academic_periods,id',
        ]);

        try {
            GradingService::calculateAndArchiveClassSubjectAverages(
                $validated['school_id'],
                $validated['school_class_id'],
                $validated['school_subject_id'],
                $validated['academic_period_id']
            );

            return redirect()->route('teacher.grades.subject-summary', [
                'school_class_id'    => $validated['school_class_id'],
                'school_subject_id'  => $validated['school_subject_id'],
                'academic_period_id' => $validated['academic_period_id'],
            ])->with('success', 'Les moyennes et rangs ont été calculés et archivés avec succès.');

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du calcul : ' . $e->getMessage());
        }
    } 

    

    public function getSchoolDetails(Request $request)
    {
        $schoolId = $request->get('school_id');

        if (!$schoolId) {
            return response()->json(['classes' => [], 'periods' => []]);
        }

        $classes = SchoolClass::where('school_id', $schoolId)->get();
        
        // Si la colonne 'is_active' existe, gardez le filtre, sinon retirez ->where('is_active', true)
        $periods = AcademicPeriod::where('school_id', $schoolId)
            ->with('periodTypeItem')
            ->get();

        return response()->json([
            'classes' => $classes,
            'periods' => $periods,
        ]);
    }

    /**
     * Récupérer les matières (ECUE) associées à une classe via les créneaux d'emploi du temps (Schedule).
     */
    public function getClassSubjects(Request $request)
    {
        $classId = $request->get('school_class_id');

        if (!$classId) {
            return response()->json(['subjects' => []]);
        }

        // Récupération des IDs des matières uniques associées à cette classe
        $subjectIds = Schedule::where('school_class_id', $classId)
            ->whereNotNull('school_subject_id')
            ->pluck('school_subject_id')
            ->unique();

        // Charger les objets matières avec leur relation (ex: subject) si elle existe
        $subjects = SchoolSubject::whereIn('id', $subjectIds)
            ->with('subject')
            ->get();

        return response()->json([
            'subjects' => $subjects
        ]);
    }
}