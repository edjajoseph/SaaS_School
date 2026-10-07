<?php

namespace App\Modules\School\Http\Controllers\Web\Document;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class TeacherDocumentController extends Controller
{
    private function getCurrentSchoolId(Request $request)
    {
        return $request->input('school_id') ?? session('current_school_id') ?? auth()->user()->school_id;
    }

    
    /**********************************************************************************
     * Liste des bulletins de paie attribués à l'enseignant (Permanents & Vacataires) *
     **********************************************************************************/

    public function payslips(Request $request)
    {
        $staffId = auth()->id();
        $schoolId = $this->getCurrentSchoolId($request);

        // 1. Bulletins des Permanents (issus de la table payrolls)
        $permanentPayslips = DB::table('payrolls')
            ->where('staff_id', $staffId)
            ->when($schoolId, fn($q) => $q->where('school_id', $schoolId))
            ->whereNull('deleted_at')
            ->select(
                'id',
                'payroll_number',
                'period_start',
                'period_end',
                'base_salary',
                'net_amount',
                'status',
                'payment_date'
            )
            ->orderBy('period_start', 'desc')
            ->get();

        // 2. Décomptes mensuels des Vacataires (agrégés depuis teacher_attendances & teacher_subject_rates)
        $vacatairePayslips = DB::table('teacher_attendances')
            ->join('schedules', 'teacher_attendances.schedule_id', '=', 'schedules.id')
            ->join('teacher_subject_rates', function ($join) {
                $join->on('schedules.staff_id', '=', 'teacher_subject_rates.staff_id')
                    ->on('schedules.school_class_id', '=', 'teacher_subject_rates.school_class_id')
                    ->on('schedules.school_subject_id', '=', 'teacher_subject_rates.school_subject_id');
            })
            ->where('teacher_attendances.staff_id', $staffId)
            ->when($schoolId, fn($q) => $q->where('schedules.school_id', $schoolId))
            ->whereNull('teacher_attendances.deleted_at')
            ->select(
                DB::raw("YEAR(teacher_attendances.date) as period_year"),
                DB::raw("MONTH(teacher_attendances.date) as period_month"),
                DB::raw("SUM(teacher_attendances.hours_done) as total_hours"),
                DB::raw("SUM(
                    CASE 
                        WHEN schedules.session_type = 'CM' THEN teacher_attendances.hours_done * teacher_subject_rates.rate_cm
                        WHEN schedules.session_type = 'TD' THEN teacher_attendances.hours_done * teacher_subject_rates.rate_td
                        WHEN schedules.session_type = 'TP' THEN teacher_attendances.hours_done * teacher_subject_rates.rate_tp
                        WHEN schedules.session_type = 'EXAMEN' THEN teacher_attendances.hours_done * teacher_subject_rates.rate_examen
                        ELSE 0 
                    END
                ) as net_amount")
            )
            ->groupBy(DB::raw("YEAR(teacher_attendances.date)"), DB::raw("MONTH(teacher_attendances.date)"))
            ->orderBy('period_year', 'desc')
            ->orderBy('period_month', 'desc')
            ->get();

        return view('School::reporting.teacher.payslips', compact('permanentPayslips', 'vacatairePayslips'));
    }

    /**
     * Téléchargement du Bulletin Permanent (PDF)
     */
    public function downloadPermanentPayslip($id)
    {
        $staffId = auth()->id();
        $payroll = DB::table('payrolls')
            ->join('users', 'payrolls.staff_id', '=', 'users.id')
            ->leftJoin('schools', 'payrolls.school_id', '=', 'schools.id')
            ->where('payrolls.id', $id)
            ->where('payrolls.staff_id', $staffId)
            ->select('payrolls.*', 'users.name as staff_name', 'schools.name as school_name', 'schools.logo as school_logo')
            ->firstOrFail();

        $items = DB::table('payroll_items')
            ->where('payroll_id', $id)
            ->get();

        $pdf = Pdf::loadView('School::pdf.payslip_permanent', compact('payroll', 'items'));
        return $pdf->stream("Bulletin_Paie_{$payroll->payroll_number}.pdf");
    }

    /**
     * Téléchargement du Décompte Vacataire (PDF)
     */
    public function downloadVacatairePayslip($year, $month)
    {
        $staffId = auth()->id();
        $schoolId = $this->getCurrentSchoolId(request());

        $school = DB::table('schools')->where('id', $schoolId)->first();
        $user = DB::table('users')->where('id', $staffId)->first();

        $details = DB::table('teacher_attendances')
            ->join('schedules', 'teacher_attendances.schedule_id', '=', 'schedules.id')
            ->join('school_classes', 'schedules.school_class_id', '=', 'school_classes.id')
            ->join('subjects', 'schedules.school_subject_id', '=', 'subjects.id')
            ->leftJoin('teacher_subject_rates', function ($join) {
                $join->on('schedules.staff_id', '=', 'teacher_subject_rates.staff_id')
                    ->on('schedules.school_class_id', '=', 'teacher_subject_rates.school_class_id')
                    ->on('schedules.school_subject_id', '=', 'teacher_subject_rates.school_subject_id');
            })
            ->where('teacher_attendances.staff_id', $staffId)
            ->whereYear('teacher_attendances.date', $year)
            ->whereMonth('teacher_attendances.date', $month)
            ->whereNull('teacher_attendances.deleted_at')
            ->select(
                'teacher_attendances.date',
                'teacher_attendances.hours_done',
                'schedules.session_type',
                'school_classes.name as class_name',
                'subjects.name as subject_name',
                'teacher_subject_rates.rate_cm',
                'teacher_subject_rates.rate_td',
                'teacher_subject_rates.rate_tp',
                'teacher_subject_rates.rate_examen'
            )
            ->orderBy('teacher_attendances.date', 'asc')
            ->get();

        $pdf = Pdf::loadView('School::pdf.payslip_vacataire', compact('school', 'user', 'details', 'year', 'month'));
        return $pdf->stream("Decompte_Vacations_{$month}_{$year}.pdf");
    }

    /***************************************************************************************
     * Affichage du formulaire et de la liste d'élèves par classe avec filtre d'école       *     
     ***************************************************************************************/

    public function classList(Request $request)
    {
        $authId = auth()->id();
        $staffId = User::where('id', $authId)->first()->personne_id;
        
        // Récupération des paramètres envoyés via GET
        $selectedSchoolId = $request->input('school_id');
        $selectedClassId = $request->input('school_class_id');

        // 1. Récupérer les écoles où l'enseignant intervient
        $schools = DB::table('schedules')
            ->join('schools', 'schedules.school_id', '=', 'schools.id')
            ->where('schedules.staff_id', $staffId)
            ->whereNull('schedules.deleted_at')
            ->select('schools.id', 'schools.name')
            ->distinct()
            ->orderBy('schools.name')
            ->get();

        // 2. Récupérer les classes filtrées par l'école sélectionnée
        $classes = collect();
        if ($selectedSchoolId) {
            $classes = DB::table('schedules')
                ->join('school_classes', 'schedules.school_class_id', '=', 'school_classes.id')
                ->where('schedules.staff_id', $staffId)
                ->where('schedules.school_id', $selectedSchoolId)
                ->whereNull('schedules.deleted_at')
                ->select('school_classes.id', 'school_classes.name')
                ->distinct()
                ->orderBy('school_classes.name')
                ->get();
        }

        $students = collect();
        $selectedClass = null;

        // 3. Charger les élèves si une classe valide est sélectionnée
        if ($selectedSchoolId && $selectedClassId) {
            $selectedClass = DB::table('school_classes')->where('id', $selectedClassId)->first();

            $students = DB::table('registrations')
                ->join('students', 'registrations.student_id', '=', 'students.id')
                ->join('personnes', 'students.personne_id', '=', 'personnes.id')
                ->where('registrations.school_class_id', $selectedClassId)
                ->where('registrations.school_id', $selectedSchoolId)
                ->whereNull('registrations.deleted_at')
                ->select(
                    'students.id',
                    'students.matricule',
                    'personnes.prenoms',
                    'personnes.nom',
                    'personnes.sexe',
                    'personnes.birth_date'
                )
                ->orderBy('personnes.nom')
                ->orderBy('personnes.prenoms')
                ->get();
        }

        return view('School::reporting.teacher.class_lists', compact(
            'schools', 
            'classes', 
            'students', 
            'selectedSchoolId', 
            'selectedClassId', 
            'selectedClass'
        ));
    }

    /**
     * Impression / Export PDF de la liste de la classe sélectionnée
     */
    public function printClassList(Request $request, $classId)
    {
        $schoolId = $request->input('school_id');
        $school = DB::table('schools')->where('id', $schoolId)->first();
        $schoolClass = DB::table('school_classes')->where('id', $classId)->firstOrFail();

        $students = DB::table('registrations')
            ->join('students', 'registrations.student_id', '=', 'students.id')
            ->join('personnes', 'students.personne_id', '=', 'personnes.id')
            ->where('registrations.school_class_id', $classId)
            ->when($schoolId, fn($q) => $q->where('registrations.school_id', $schoolId))
            ->whereNull('registrations.deleted_at')
            ->select(
                'students.matricule',
                'personnes.nom',
                'personnes.prenoms',
                'personnes.sexe',
                'personnes.birth_date'
            )
            ->orderBy('personnes.nom')
            ->orderBy('personnes.prenoms')
            ->get();

        $pdf = Pdf::loadView('School::reporting.teacher.class_list_print', compact('school', 'schoolClass', 'students'));
        return $pdf->stream("Liste_Classe_{$schoolClass->name}.pdf");
    }

    /*************************************************************************************************
     * Formulaire et affichage des notes par évaluation                                              *
     *************************************************************************************************/

    public function evaluationGrades(Request $request)
    {
        $authId = auth()->id();
        $staffId = User::where('id', $authId)->first()->personne_id;

        $selectedSchoolId = $request->input('school_id');
        $selectedClassId = $request->input('school_class_id');
        $selectedEvaluationId = $request->input('evaluation_id');

        // 1. Écoles où l'enseignant intervient
        $schools = DB::table('schedules')
            ->join('schools', 'schedules.school_id', '=', 'schools.id')
            ->where('schedules.staff_id', $staffId)
            ->whereNull('schedules.deleted_at')
            ->select('schools.id', 'schools.name')
            ->distinct()
            ->orderBy('schools.name')
            ->get();

        // 2. Classes de l'école sélectionnée où l'enseignant intervient
        $classes = collect();
        if ($selectedSchoolId) {
            $classes = DB::table('schedules')
                ->join('school_classes', 'schedules.school_class_id', '=', 'school_classes.id')
                ->where('schedules.staff_id', $staffId)
                ->where('schedules.school_id', $selectedSchoolId)
                ->whereNull('schedules.deleted_at')
                ->select('school_classes.id', 'school_classes.name')
                ->distinct()
                ->orderBy('school_classes.name')
                ->get();
        }

        // 3. Évaluations programmées pour cette classe et cette école
        $evaluations = collect();
        if ($selectedSchoolId && $selectedClassId) {
            $evaluations = DB::table('evaluations')
                ->join('evaluation_types', 'evaluations.evaluation_type_id', '=', 'evaluation_types.id')
                ->join('school_subjects', 'evaluations.school_subject_id', '=', 'school_subjects.id')
                ->where('evaluations.school_id', $selectedSchoolId)
                ->where('evaluations.school_class_id', $selectedClassId)
                ->whereNull('evaluations.deleted_at')
                ->select(
                    'evaluations.*',
                    'evaluation_types.name as type_name',
                    'school_subjects.custom_name as subject_name'
                )
                ->orderBy('evaluations.evaluated_at', 'desc')
                ->get();
        }

        $selectedEvaluation = null;
        $grades = collect();

        // 4. Charger la liste des élèves et leurs notes pour l'évaluation sélectionnée
        if ($selectedEvaluationId) {
            $selectedEvaluation = DB::table('evaluations')
                ->join('evaluation_types', 'evaluations.evaluation_type_id', '=', 'evaluation_types.id')
                ->join('school_subjects', 'evaluations.school_subject_id', '=', 'school_subjects.id')
                ->join('school_classes', 'evaluations.school_class_id', '=', 'school_classes.id')
                ->where('evaluations.id', $selectedEvaluationId)
                ->select(
                    'evaluations.*',
                    'evaluation_types.name as type_name',
                    'school_subjects.custom_name as subject_name',
                    'school_classes.name as class_name'
                )
                ->first();

            if ($selectedEvaluation) {
                $grades = DB::table('registrations')
                    ->join('students', 'registrations.student_id', '=', 'students.id')
                    ->join('personnes', 'students.personne_id', '=', 'personnes.id')
                    ->leftJoin('grades', function ($join) use ($selectedEvaluationId) {
                        $join->on('registrations.id', '=', 'grades.registration_id') // Corrected join condition
                            ->where('grades.evaluation_id', '=', $selectedEvaluationId)
                            ->whereNull('grades.deleted_at');
                    })
                    ->where('registrations.school_class_id', $selectedClassId)
                    ->where('registrations.school_id', $selectedSchoolId)
                    ->whereNull('registrations.deleted_at')
                    ->select(
                        'registrations.id as registration_id',
                        'students.id as student_id',
                        'students.matricule',
                        'personnes.nom',
                        'personnes.prenoms',
                        'grades.id as grade_id',
                        'grades.score',
                        'grades.is_absent',
                        'grades.is_justified',
                        'grades.remarks'
                    )
                    ->orderBy('personnes.nom')
                    ->orderBy('personnes.prenoms')
                    ->get();
            }
        }

        return view('School::reporting.teacher.evaluation_grades', compact(
            'schools',
            'classes',
            'evaluations',
            'grades',
            'selectedSchoolId',
            'selectedClassId',
            'selectedEvaluationId',
            'selectedEvaluation'
        ));
    }

    /**
     * Export PDF de la liste des notes
     */
    public function printEvaluationGrades(Request $request, $evaluationId)
    {
        $evaluation = DB::table('evaluations')
            ->join('evaluation_types', 'evaluations.evaluation_type_id', '=', 'evaluation_types.id')
            ->join('school_subjects', 'evaluations.school_subject_id', '=', 'school_subjects.id')
            ->join('school_classes', 'evaluations.school_class_id', '=', 'school_classes.id')
            ->where('evaluations.id', $evaluationId)
            ->select(
                'evaluations.*',
                'evaluation_types.name as type_name',
                'school_subjects.custom_name as subject_name',
                'school_classes.name as class_name'
            )
            ->firstOrFail();

        $school = DB::table('schools')->where('id', $evaluation->school_id)->first();

        $grades = DB::table('registrations')
        ->join('students', 'registrations.student_id', '=', 'students.id')
        ->join('personnes', 'students.personne_id', '=', 'personnes.id')
        ->leftJoin('grades', function ($join) use ($evaluationId) {
            $join->on('registrations.id', '=', 'grades.registration_id') // 1. Liaison sur registration_id
                ->where('grades.evaluation_id', '=', $evaluationId)
                ->whereNull('grades.deleted_at');
        })
        ->where('registrations.school_class_id', $evaluation->school_class_id)
        ->where('registrations.school_id', $evaluation->school_id)
        ->whereNull('registrations.deleted_at')
        ->select(
            'students.matricule',
            'personnes.nom',
            'personnes.prenoms',
            'grades.score',
            'grades.is_absent',
            'grades.remarks' // 2. Remplacement de observation par remarks
        )
        ->orderBy('personnes.nom')
        ->orderBy('personnes.prenoms')
        ->get();

        $pdf = Pdf::loadView('School::reporting.teacher.evaluation_grades_print', compact('school', 'evaluation', 'grades'));
        return $pdf->stream("Notes_{$evaluation->class_name}_{$evaluation->title}.pdf");
    }
}