<?php

namespace App\Modules\School\Http\Controllers\Web\Logbook;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\Schedule;
use App\Modules\School\Models\SchoolClass;
use App\Modules\School\Models\SchoolSubject;
use App\Modules\School\Models\Staff;
use App\Modules\School\Models\School;
use App\Modules\School\Models\TeacherAttendance;
use App\Modules\School\Services\PedagogicalProgressService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class TeacherAttendanceController extends Controller
{
    protected PedagogicalProgressService $progressService;

    public function __construct(PedagogicalProgressService $progressService)
    {
        $this->progressService = $progressService;
    }

    /**
     * Liste et suivi du cahier de textes avec consolidation pédagogique.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $isAdminOrDirector = $user->hasRole('admin-ecole') 
                            || $user->hasRole('directeur-etudes') 
                            || $user->hasRole('chef_departement') 
                            || $user->can('validate-logbook');

        $selectedSchoolId  = $request->input('school_id');
        $selectedClassId   = $request->input('school_class_id');
        $selectedSubjectId = $request->input('school_subject_id');
        $selectedStaffId   = $request->input('staff_id');
        $selectedStatus    = $request->input('status');
        $plannedHours      = (float) $request->input('planned_hours', 30);

        // 1. Construction de la requête avec la relation school
        $query = TeacherAttendance::with(['staff.personne', 'schoolClass', 'subject', 'validator', 'schoolClass.school'])
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc');

        // Restreindre aux émargements de l'enseignant connecté s'il n'est ni Admin ni Directeur
        if (!$isAdminOrDirector && $user->staff) {
            $query->forStaff($user->staff->id);
        }

        // 2. Application des filtres
        if ($selectedSchoolId) {
            $query->whereHas('schoolClass', function ($q) use ($selectedSchoolId) {
                $q->where('school_id', $selectedSchoolId);
            });
        }

        if ($selectedClassId) {
            $query->where('school_class_id', $selectedClassId);
        }

        if ($selectedSubjectId) {
            $query->where('school_subject_id', $selectedSubjectId);
        }

        if ($selectedStaffId && $isAdminOrDirector) {
            $query->where('staff_id', $selectedStaffId);
        }

        if ($selectedStatus) {
            if ($selectedStatus === 'validated') {
                $query->where('is_validated', true);
            } elseif ($selectedStatus === 'pending') {
                $query->where('is_validated', false);
            }
        }

        // 3. Calcul des statistiques sur le jeu de données filtré
        $statsQuery = clone $query;
        $stats = [
            'total_sessions'  => $statsQuery->count(),
            'total_hours'     => (float) $statsQuery->sum('hours_done'),
            'validated_count' => (float) (clone $statsQuery)->where('is_validated', true)->count(),
            'pending_count'   => (float) (clone $statsQuery)->where('is_validated', false)->count(),
        ];
        $stats['validation_rate'] = $stats['total_sessions'] > 0 
            ? round(($stats['validated_count'] / $stats['total_sessions']) * 100, 1) 
            : 0;

        // 4. Pagination
        $attendances = $query->paginate(15)->appends($request->all());

        // 5. Chargement des données de filtres (Écoles, Classes, Matières, Enseignants)
        $schools  = School::where('is_active', true)->get();
        
        $classesQuery = SchoolClass::where('is_active', true);
        if ($selectedSchoolId) {
            $classesQuery->where('school_id', $selectedSchoolId);
        }
        $classes  = $classesQuery->get();
        
        $subjects = SchoolSubject::all();
        $staffs   = $isAdminOrDirector ? Staff::with('personne')->get() : collect();

        // 6. Service de progression pédagogique (Ne prend en compte QUE les séances visées)
        $progressData = null;
        if ($selectedClassId && $selectedSubjectId) {
            $progressData = $this->progressService->getSubjectProgressForClass(
                (int) $selectedClassId,
                (int) $selectedSubjectId,
                $plannedHours,
                true, // onlyValidated = true
                $selectedStaffId ? (int) $selectedStaffId : null
            );
        }

        return view('School::logbook.index', compact(
            'attendances',
            'schools',
            'classes',
            'subjects',
            'staffs',
            'selectedSchoolId',
            'selectedClassId',
            'selectedSubjectId',
            'selectedStaffId',
            'selectedStatus',
            'plannedHours',
            'progressData',
            'isAdminOrDirector',
            'stats'
        ));
    }

    /**
     * Formulaire de saisie d'une séance.
     */
    public function create(Request $request)
    {
        $scheduleId = $request->input('schedule_id');
        $schedule   = $scheduleId ? Schedule::with(['schoolClass', 'subject', 'staff'])->find($scheduleId) : null;

        $classes = SchoolClass::where('is_active', true)->orderBy('name')->get();
        $staffs  = Staff::with('personne')->get();
        $schools = School::where('is_active', true)->orderBy('name')->get();

        $query = SchoolSubject::with('subject');

        if ($schedule && $schedule->school_class_id) {
            $query->where(function($q) use ($schedule) {
                $q->where('school_class_id', $schedule->school_class_id)
                ->orWhereNull('school_class_id');
            });
        }

        $subjects = $query->get()->sortBy(function($schoolSubject) {
            return $schoolSubject->subject?->name;
        });

        return view('School::logbook.create', compact('schools', 'schedule', 'classes', 'subjects', 'staffs'));
    }

    /**
     * Enregistrement d'une séance dans le cahier de textes.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id'         => 'required|exists:schools,id',
            'school_class_id'   => 'required|exists:school_classes,id',
            'school_subject_id' => 'required|exists:school_subjects,id',
            'schedule_id'       => 'nullable|exists:schedules,id',
            'staff_id'          => 'nullable|exists:staff,id',
            'date'              => 'required|date',
            'start_time'        => 'required|date_format:H:i',
            'end_time'          => 'required|date_format:H:i|after:start_time',
            'session_type'      => 'required|in:CM,TD,TP,EXAMEN',
            'chapter_title'     => 'nullable|string|max:255',
            'topic_covered'     => 'required|string',
            'objectives'        => 'nullable|string',
            'homework'          => 'nullable|string',
            'homework_due_date' => 'nullable|date|after_or_equal:date',
        ]);

        $user = auth()->user();
        $staffId = $user->staff->id ?? $request->input('staff_id');

        if (!$staffId) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Impossible d\'enregistrer la séance : Votre compte utilisateur n\'est pas associé à un profil d\'enseignant (Staff).');
        }

        $start = Carbon::parse($validated['date'] . ' ' . $validated['start_time']);
        $end   = Carbon::parse($validated['date'] . ' ' . $validated['end_time']);
        $hours = round($start->diffInMinutes($end) / 60, 2);

        $class = SchoolClass::findOrFail($validated['school_class_id']);
        $schoolId = $user->school_id ?? $class->school_id;

        $attendance = TeacherAttendance::create([
            'school_id'         => $schoolId,
            'staff_id'          => $staffId,
            'school_class_id'   => $validated['school_class_id'],
            'school_subject_id' => $validated['school_subject_id'],
            'schedule_id'       => $validated['schedule_id'] ?? null,
            'date'              => $validated['date'],
            'start_time'        => $validated['start_time'],
            'end_time'          => $validated['end_time'],
            'hours_done'        => $hours,
            'session_type'      => $validated['session_type'],
            'chapter_title'     => $validated['chapter_title'] ?? null,
            'topic_covered'     => $validated['topic_covered'],
            'objectives'        => $validated['objectives'] ?? null,
            'homework'          => $validated['homework'] ?? null,
            'homework_due_date' => $validated['homework_due_date'] ?? null,
            'is_validated'      => false,
        ]);

        // Mise à jour : ne prendra en compte que si la séance venait à être créée déjà validée
        $this->progressService->updateProgress(
            (int) $attendance->school_class_id,
            (int) $attendance->school_subject_id,
            (int) $attendance->staff_id,
            true // onlyValidated = true
        );

        return redirect()->route('school.logbook.index', [
            'school_class_id'   => $validated['school_class_id'],
            'school_subject_id' => $validated['school_subject_id']
        ])->with('success', 'Séance enregistrée avec succès dans le cahier de textes (En attente de visa).');
    }

    /**
     * Application du visa individuel par le Directeur des Études / Chef de Dépt.
     */
    public function validateEntry(Request $request, TeacherAttendance $attendance)
    {
        $request->validate([
            'validation_notes' => 'nullable|string|max:500',
        ]);

        $attendance->markAsValidated(auth()->id(), $request->input('validation_notes'));

        // Recalcul et persistance : prend uniquement en compte les séances visées
        $this->progressService->updateProgress(
            (int) $attendance->school_class_id,
            (int) $attendance->school_subject_id,
            (int) $attendance->staff_id,
            true // onlyValidated = true
        );

        return redirect()->back()->with('success', 'La séance a été visée et la progression enregistrée.');
    }

    /**
     * Validation ou Révocation groupée des visages pédagogiques
     */
    /**
     * Validation ou Révocation groupée des visas pédagogiques
     */
    public function bulkValidate(Request $request)
    {
        $user = auth()->user();
        
        $allowedRoles = ['admin', 'admin-ecole', 'directeur_etudes', 'directeur-etudes', 'chef_departement', 'chef-departement'];
        if (!$user->hasRole($allowedRoles) && !$user->isAbleTo('validate-logbook')) {
            abort(403, 'Action non autorisée.');
        }

        $request->validate([
            'attendance_ids'   => 'required|array',
            'attendance_ids.*' => 'exists:teacher_attendances,id',
            'action'           => 'required|in:validate,unvalidate',
            'bulk_notes'       => 'nullable|string|max:500',
        ]);

        $action = $request->input('action');
        $ids    = $request->input('attendance_ids');

        $attendances = TeacherAttendance::whereIn('id', $ids)->get();

        DB::transaction(function () use ($action, $ids, $request, $attendances) {
            if ($action === 'validate') {
                TeacherAttendance::whereIn('id', $ids)->update([
                    'is_validated'     => true,
                    'validated_by'     => auth()->id(),
                    'validated_at'     => now(),
                    'validation_notes' => $request->input('bulk_notes'),
                ]);
            } else {
                // Révocation groupée
                TeacherAttendance::whereIn('id', $ids)->update([
                    'is_validated'     => false,
                    'validated_by'     => null,
                    'validated_at'     => null,
                    'validation_notes' => null,
                ]);
            }

            // Recalcul des progressions et mises à jour dans TeacherSubjectRate
            $impactedGroups = $attendances->groupBy(function ($item) {
                return $item->school_class_id . '-' . $item->school_subject_id . '-' . $item->staff_id;
            });

            foreach ($impactedGroups as $group) {
                $first = $group->first();
                $this->progressService->updateProgress(
                    (int) $first->school_class_id,
                    (int) $first->school_subject_id,
                    (int) $first->staff_id,
                    true // Calcule dynamiquement le total des émargements encore visés
                );
            }
        });

        $msg = ($action === 'validate') 
            ? count($ids) . ' séance(s) visée(s) et heures ajoutées dans TeacherSubjectRate.' 
            : count($ids) . ' visa(s) révoqué(s) et heures annulées dans TeacherSubjectRate.';

        return redirect()->back()->with('success', $msg);
    }

    /**
     * Révocation individuelle d'un visa et annulation des heures comptabilisées dans TeacherSubjectRate
     */
    public function unvalidate($id)
    {
        $user = auth()->user();

        // Vérification de rôle & permission via Laratrust
        $allowedRoles = ['admin', 'admin-ecole', 'directeur_etudes', 'directeur-etudes', 'chef_departement', 'chef-departement'];
        if (!$user->hasRole($allowedRoles) && !$user->isAbleTo('validate-logbook')) {
            abort(403, 'Action non autorisée.');
        }

        $attendance = TeacherAttendance::findOrFail($id);

        DB::transaction(function () use ($attendance) {
            // 1. Invalidation du visa de la séance
            $attendance->update([
                'is_validated'     => false,
                'validated_by'     => null,
                'validated_at'     => null,
                'validation_notes' => null,
            ]);

            // 2. Recalcul et déduction automatique des heures dans TeacherSubjectRate (onlyValidated = true)
            $this->progressService->updateProgress(
                (int) $attendance->school_class_id,
                (int) $attendance->school_subject_id,
                (int) $attendance->staff_id,
                true // Prend uniquement en compte les séances toujours visées, annulant ainsi celle-ci
            );
        });

        return redirect()->back()->with('success', 'Le visa a été révoqué et les heures de la séance ont été soustraites de TeacherSubjectRate.');
    }

    /**
     * Exporte le cahier de textes au format PDF.
     */
    public function exportPdf(Request $request)
    {
        $query = TeacherAttendance::with(['staff.personne', 'schoolClass', 'subject']);

        if ($request->filled('school_class_id')) {
            $query->where('school_class_id', $request->school_class_id);
        }

        if ($request->filled('school_subject_id')) {
            $query->where('school_subject_id', $request->school_subject_id);
        }

        if ($request->filled('staff_id')) {
            $query->where('staff_id', $request->staff_id);
        }

        if ($request->filled('date_start')) {
            $query->whereDate('date', '>=', $request->date_start);
        }

        if ($request->filled('date_end')) {
            $query->whereDate('date', '<=', $request->date_end);
        }

        $attendances = $query->orderBy('date', 'asc')->get();

        $pdf = Pdf::loadView('School::logbook.pdf', compact('attendances'));

        return $pdf->download('cahier_de_textes_' . now()->format('Y_m_d_His') . '.pdf');
    }
}