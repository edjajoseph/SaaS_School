<?php

namespace App\Modules\School\Http\Controllers\Web\Evaluation;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\Registration;
use App\Modules\School\Models\SchoolClass;
use App\Modules\School\Models\AcademicPeriod;
use App\Modules\School\Models\AcademicYear;
use App\Modules\School\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportCardController extends Controller
{
    public function index(Request $request)
    {
        $isAdmin = auth()->user()->hasRole('admin');
        $schools = $isAdmin ? School::all() : collect();
        
        $schoolId = $request->input('school_id', Auth::user()->school_id ?? 1);
        $academicYearId = $request->input('academic_year_id');
        $classId = $request->input('school_class_id');

        // 1. Récupération de toutes les années académiques (pas de colonne school_id dans academic_years)
        $academicYears = AcademicYear::orderBy('start_date', 'desc')->get();

        // 2. Récupération des classes filtrées par établissement si présent
        $classes = $schoolId 
            ? SchoolClass::where('school_id', $schoolId)->get() 
            : SchoolClass::all();

        // 3. Récupération des périodes filtrées par année académique (et établissement si applicable)
        $periods = collect();
        if ($academicYearId) {
            $periodsQuery = AcademicPeriod::where('academic_year_id', $academicYearId);
            if ($schoolId) {
                $periodsQuery->where('school_id', $schoolId);
            }
            $periods = $periodsQuery->get();
        }
        
        // 4. Récupération des étudiants
        $students = collect();
        if ($classId && $academicYearId) {
            $studentsQuery = Registration::with('student.personne')
                ->where('school_class_id', $classId)
                ->where('academic_year_id', $academicYearId)
                ->where('status', 'confirmed');

            if ($schoolId) {
                $studentsQuery->where('school_id', $schoolId);
            }

            $students = $studentsQuery->get();
        }

        return view('School::evaluation.reports.index', compact(
            'isAdmin',
            'schools',
            'schoolId',
            'classes',
            'academicYears',
            'periods',
            'students'
        ));
    }

    /**
     * AJAX: Classes associées à l'établissement
     */
    public function getSchoolOptions(Request $request)
    {
        $schoolId = $request->input('school_id');

        $classes = SchoolClass::where('school_id', $schoolId)
            ->get(['id', 'name']);

        return response()->json([
            'classes' => $classes
        ]);
    }

    /**
     * AJAX: Périodes associées à l'année académique et l'établissement
     */
    public function getPeriodsByYear(Request $request)
    {
        $academicYearId = $request->input('academic_year_id');
        $schoolId = $request->input('school_id', Auth::user()->school_id ?? 1);

        $query = AcademicPeriod::where('academic_year_id', $academicYearId)
            ->with('periodTypeItem');

        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }

        $periods = $query->get()->map(function ($period) {
            return [
                'id' => $period->id,
                'name' => $period->periodTypeItem->name ?? $period->name ?? 'Période '.$period->id,
            ];
        });

        return response()->json($periods);
    }

    public function generateBulletin(Registration $registration, AcademicPeriod $academicPeriod)
    {
        $registration->load(['student.personne', 'schoolClass.school']);

        // Charger la vue Blade avec les données et générer le rendu PDF
        $pdf = Pdf::loadView('School::evaluation.reports.pdf_bulletin', compact('registration', 'academicPeriod'))
                  ->setPaper('a4', 'portrait');

        // stream() affiche le PDF directement dans le navigateur (ou download() pour forcer le téléchargement)
        return $pdf->stream('Bulletin_' . ($registration->student->matricule ?? 'Etudiant') . '.pdf');
    }

    /**
     * Génération du Relevé LMD (PDF via DomPDF)
     */
    public function generateReleveLmd(Registration $registration, AcademicPeriod $academicPeriod)
    {
        $registration->load(['student.personne', 'schoolClass.school']);

        $pdf = Pdf::loadView('School::evaluation.reports.pdf_releve_lmd', compact('registration', 'academicPeriod'))
                  ->setPaper('a4', 'portrait');

        return $pdf->stream('Releve_LMD_' . ($registration->student->matricule ?? 'Etudiant') . '.pdf');
    }

    /**
     * Génération du PV de Délibération (PDF via DomPDF)
     */
    public function generatePvDeliberation(SchoolClass $schoolClass, AcademicPeriod $academicPeriod)
    {
        $schoolClass->load('school');
        $registrations = Registration::with('student.personne')
            ->where('school_class_id', $schoolClass->id)
            ->where('status', 'confirmed')
            ->get();

        // Mode paysage (landscape) recommandé pour les PV de délibération à plusieurs colonnes
        $pdf = Pdf::loadView('School::evaluation.reports.pdf_pv_deliberation', compact('schoolClass', 'academicPeriod', 'registrations'))
                  ->setPaper('a4', 'landscape');

        return $pdf->stream('PV_Deliberation_' . $schoolClass->name . '.pdf');
    }
}