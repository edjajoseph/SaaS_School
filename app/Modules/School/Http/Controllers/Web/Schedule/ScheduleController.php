<?php

namespace App\Modules\School\Http\Controllers\Web\Schedule;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\Schedule;
use App\Modules\School\Models\School;
use App\Modules\School\Models\SchoolClass;
use App\Modules\School\Models\SchoolSubject;
use App\Modules\School\Models\Staff;
use App\Modules\School\Models\Room;
use App\Modules\School\Models\AcademicPeriod;
use App\Modules\School\Services\ScheduleConflictService;
use App\Modules\School\Services\MoodleCalendarService;
use App\Modules\School\Services\CourseAssignmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    protected MoodleCalendarService $moodleService;
    protected CourseAssignmentService $courseAssignmentService;

    public function __construct(
        MoodleCalendarService $moodleService,
        CourseAssignmentService $courseAssignmentService
    ) {
        $this->moodleService = $moodleService;
        $this->courseAssignmentService = $courseAssignmentService;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole('admin-ecole') || $user->hasRole('super-admin') || $user->is_admin;

        $query = Schedule::with(['school', 'schoolClass', 'subject', 'teacher', 'room', 'academicPeriod']);

        if ($isAdmin) {
            if ($request->filled('school_id')) {
                $query->where('school_id', $request->input('school_id'));
            }
            $schools = School::orderBy('name')->get();
        } else {
            $query->where('school_id', $user->school_id);
            $schools = collect();
        }

        if ($request->filled('day_of_week')) {
            $query->where('day_of_week', $request->input('day_of_week'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('schoolClass', function ($classQuery) use ($search) {
                    $classQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('subject', function ($subjectQuery) use ($search) {
                    $subjectQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('teacher', function ($teacherQuery) use ($search) {
                    $teacherQuery->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                });
            });
        }

        $schedules = $query->latest()->paginate(15)->withQueryString();

        return view('School::schedule.schedules.index', compact('schedules', 'schools', 'isAdmin'));
    }

    public function create(Request $request)
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole('admin-ecole') || $user->hasRole('super-admin') || $user->is_admin;

        if ($isAdmin) {
            $schools = School::orderBy('name')->get();
            $selectedSchoolId = $request->input('school_id');
            
            $classes = $selectedSchoolId ? SchoolClass::where('school_id', $selectedSchoolId)->get() : SchoolClass::all();
            $subjects = $selectedSchoolId ? SchoolSubject::where('school_id', $selectedSchoolId)->get() : SchoolSubject::all();
            $teachers = $selectedSchoolId ? Staff::where('school_id', $selectedSchoolId)->get() : Staff::all();
            $rooms = $selectedSchoolId 
                ? Room::where('school_id', $selectedSchoolId)->where('is_active', true)->get() 
                : Room::where('is_active', true)->get();
            $academicPeriods = $selectedSchoolId ? AcademicPeriod::where('school_id', $selectedSchoolId)->get() : AcademicPeriod::all();
        } else {
            $schools = collect();
            $classes = SchoolClass::where('school_id', $user->school_id)->get();
            $subjects = SchoolSubject::where('school_id', $user->school_id)->get();
            $teachers = Staff::where('school_id', $user->school_id)->get();
            $rooms = Room::where('school_id', $user->school_id)->where('is_active', true)->get();
            $academicPeriods = AcademicPeriod::where('school_id', $user->school_id)->get();
        }

        $viewData = compact('schools', 'classes', 'subjects', 'teachers', 'rooms', 'academicPeriods', 'isAdmin');

        return view('School::schedule.schedules.create', $viewData);
    }

    /**
     * Traitement de la création avec gestion multi-créneaux (Multi-Record)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id'               => 'required|integer',
            'school_class_id'         => 'required|integer',
            'subject_id'              => 'required|integer',
            'staff_id'                => 'required|integer',
            'academic_period_id'      => 'required|integer',

            // Multi-enregistrements pour les créneaux/plages horaires
            'sessions'                => 'required|array|min:1',
            'sessions.*.session_type' => 'required|string',
            'sessions.*.day_of_week'  => 'required|integer|between:1,7',
            'sessions.*.start_time'   => 'required|date_format:H:i',
            'sessions.*.end_time'     => 'required|date_format:H:i|after:sessions.*.start_time',
            'sessions.*.room_id'      => 'nullable|integer',

            // Volumes globaux optionnels si saisis directement dans le formulaire
            'volume_cm'               => 'nullable|numeric|min:0',
            'volume_td'               => 'nullable|numeric|min:0',
            'volume_tp'               => 'nullable|numeric|min:0',
            'volume_examen'           => 'nullable|numeric|min:0',
        ]);

        $mapSessionType = [
            'Cours' => 'CM', 'Cours Magistral' => 'CM', 'CM' => 'CM',
            'TD' => 'TD', 'Travaux Dirigés' => 'TD', 'TP' => 'TP', 'Travaux Pratiques' => 'TP',
            'CC' => 'EXAM', 'EXAM' => 'EXAM', 'Examen' => 'EXAM',
        ];

        // Volumes par défaut calculés si non saisis explicitement
        $calculatedVolumes = [
            'volume_cm'     => (float) ($request->input('volume_cm') ?? 0),
            'volume_td'     => (float) ($request->input('volume_td') ?? 0),
            'volume_tp'     => (float) ($request->input('volume_tp') ?? 0),
            'volume_examen' => (float) ($request->input('volume_examen') ?? 0),
        ];

        $schedulesToCreate = [];
        $allConflicts = [];

        // 1. Inscription, formatage et contrôle anti-chevauchement pour CHAQUE session
        foreach ($request->input('sessions') as $index => $session) {
            $sessionTypeNormalized = $mapSessionType[$session['session_type']] ?? 'CM';

            $scheduleData = [
                'school_id'          => $validated['school_id'],
                'school_class_id'    => $validated['school_class_id'],
                'school_subject_id'  => $validated['subject_id'],
                'staff_id'           => $validated['staff_id'],
                'academic_period_id' => $validated['academic_period_id'],
                'room_id'            => $session['room_id'] ?? null,
                'session_type'       => $sessionTypeNormalized,
                'day_of_week'        => $session['day_of_week'],
                'start_time'         => $session['start_time'],
                'end_time'           => $session['end_time'],
            ];

            // Calcul du nombre d'heures de la session (différence H:i) pour alimenter les volumes automatiquement
            $start = Carbon::createFromFormat('H:i', $session['start_time']);
            $end   = Carbon::createFromFormat('H:i', $session['end_time']);
            $hours = $start->diffInMinutes($end) / 60;

            if (!$request->filled('volume_cm') && $sessionTypeNormalized === 'CM')   $calculatedVolumes['volume_cm'] += $hours;
            if (!$request->filled('volume_td') && $sessionTypeNormalized === 'TD')   $calculatedVolumes['volume_td'] += $hours;
            if (!$request->filled('volume_tp') && $sessionTypeNormalized === 'TP')   $calculatedVolumes['volume_tp'] += $hours;
            if (!$request->filled('volume_examen') && $sessionTypeNormalized === 'EXAM') $calculatedVolumes['volume_examen'] += $hours;

            // Contrôle Anti-Chevauchement
            $conflicts = ScheduleConflictService::checkConflicts($scheduleData);
            if (!empty($conflicts)) {
                $allConflicts["session_{$index}"] = $conflicts;
            }

            $schedulesToCreate[] = $scheduleData;
        }

        // Si au moins un créneau entre en conflit, arrêt et retour des erreurs
        if (!empty($allConflicts)) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => $allConflicts], 422);
            }
            return back()->withErrors($allConflicts)->withInput();
        }

        // 2. Transaction SQL : Création des créneaux + Mise à jour des Taux et Volumes
        $createdSchedules = DB::transaction(function () use ($schedulesToCreate, $validated, $calculatedVolumes) {
            $created = [];

            foreach ($schedulesToCreate as $data) {
                $created[] = Schedule::create($data);
            }

            // Récupérer l'enseignant et la classe
            $staff = Staff::findOrFail($validated['staff_id']);
            $class = SchoolClass::findOrFail($validated['school_class_id']);

            // Création/Mise à jour idempotente du TeacherSubjectRate avec les volumes calculés
            $this->courseAssignmentService->assignTeacherToSubject(
                $staff,
                $class,
                $validated['subject_id'],
                $validated['academic_period_id'],
                $calculatedVolumes
            );

            return $created;
        });

        // 3. Synchronisation Moodle pour chaque créneau créé
        foreach ($createdSchedules as $schedule) {
            $moodleEventId = $this->moodleService->createEvent($schedule);
            if ($moodleEventId) {
                $schedule->update(['moodle_event_id' => $moodleEventId]);
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => count($createdSchedules) . ' créneau(x) d\'emploi du temps créé(s) et tarifs/volumes mis à jour.',
                'data'    => $createdSchedules
            ]);
        }

        return redirect()->route('schedule.schedules.index')
            ->with('success', count($createdSchedules) . ' créneau(x) d\'emploi du temps créé(s) et tarifs/volumes mis à jour.');
    }

    public function show(Schedule $schedule)
    {
        $schedule->load([
            'school',
            'schoolClass',
            'room',
            'academicPeriod',
            'subject' => function ($query) {
                $query->select('id', 'school_id', 'subject_id', 'custom_name', 'code', 'color_code')
                      ->with('subject:id,name,code');
            },
            'teacher' => function ($query) {
                $query->select('id', 'personne_id')
                      ->with('personne:id,nom,prenoms');
            }
        ]);

        return view('School::schedule.schedules.show', compact('schedule'));
    }

    public function edit(Schedule $schedule)
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole('admin-ecole') || $user->hasRole('super-admin') || $user->is_admin;
        $schoolId = $schedule->school_id;

        $schools = $isAdmin ? School::orderBy('name')->get() : collect();

        $classes = SchoolClass::where('school_id', $schoolId)->get();
        $subjects = SchoolSubject::join('subjects', 'school_subjects.subject_id', '=', 'subjects.id')
            ->where('school_subjects.school_id', $schoolId)
            ->select('school_subjects.id', 'school_subjects.custom_name')
            ->distinct()->get();
        
        $teachers = Staff::join('personnes', 'staff.personne_id', '=', 'personnes.id')
            ->join('staff_contracts', 'staff_contracts.staff_id', '=', 'staff.id')
            ->where('staff_contracts.school_id', $schoolId)
            ->select('staff.id', 'personnes.nom', 'personnes.prenoms')
            ->distinct()->get();
       
        $rooms = Room::where('school_id', $schoolId)->where('is_active', true)->get();
        $academicPeriods = AcademicPeriod::where('school_id', $schoolId)->get();

        $viewData = compact('schedule', 'schools', 'classes', 'subjects', 'teachers', 'rooms', 'academicPeriods', 'isAdmin');

        return view('School::schedule.schedules.edit', $viewData);
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'school_id'          => 'required|exists:schools,id',
            'school_class_id'    => 'required|exists:school_classes,id',
            'subject_id'         => 'required|exists:school_subjects,id',
            'staff_id'           => 'required|exists:staff,id',
            'room_id'            => 'nullable|exists:rooms,id',
            'academic_period_id' => 'required|exists:academic_periods,id',
            'session_type'       => 'required|string|max:50',
            'day_of_week'        => 'required|integer|between:1,7',
            'start_time'         => 'required|date_format:H:i',
            'end_time'           => 'required|date_format:H:i|after:start_time',
        ]);

        $data = $validated;
        $data['school_subject_id'] = $data['subject_id'];
        unset($data['subject_id']);

        // Contrôle Anti-Chevauchement
        $conflicts = ScheduleConflictService::checkConflicts($data, $schedule->id);

        if (!empty($conflicts)) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'errors' => $conflicts], 422);
            }
            return back()->withErrors($conflicts)->withInput();
        }

        DB::transaction(function () use ($schedule, $data) {
            // Ré-allocation Moodle si les horaires changent
            if ($schedule->moodle_event_id) {
                $this->moodleService->deleteEvent($schedule->moodle_event_id);
            }

            $schedule->update($data);

            // Mettre à jour/S'assurer que le TeacherSubjectRate existe aussi pour la nouvelle attribution
            $staff = Staff::findOrFail($data['staff_id']);
            $class = SchoolClass::findOrFail($data['school_class_id']);

            $this->courseAssignmentService->assignTeacherToSubject(
                $staff,
                $class,
                $data['school_subject_id'],
                $data['academic_period_id']
            );
        });

        // Re-création de l'événement mis à jour dans Moodle
        $newMoodleEventId = $this->moodleService->createEvent($schedule);
        if ($newMoodleEventId) {
            $schedule->update(['moodle_event_id' => $newMoodleEventId]);
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'L\'emploi du temps et Moodle ont été mis à jour avec succès.',
                'data'    => $schedule
            ]);
        }

        return redirect()->route('schedule.schedules.index')
            ->with('success', 'L\'emploi du temps et Moodle ont été mis à jour avec succès.');
    }

    public function toggleComplete(Schedule $schedule)
    {
        $newStatus = !$schedule->is_completed;

        if ($newStatus && $schedule->moodle_event_id) {
            $this->moodleService->deleteEvent($schedule->moodle_event_id);
            $schedule->moodle_event_id = null;
        }

        $schedule->update([
            'is_completed' => $newStatus,
            'is_active'    => !$newStatus,
        ]);

        $message = $newStatus 
            ? "L'ECUE est terminé. Le créneau est libéré et retiré de l'agenda Moodle." 
            : "L'ECUE a été réactivé.";

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => $message]);
        }

        return back()->with('success', $message);
    }

    public function destroy(Schedule $schedule)
    {
        if ($schedule->moodle_event_id) {
            $this->moodleService->deleteEvent($schedule->moodle_event_id);
        }

        $schedule->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'L\'élément a été supprimé de l\'ERP et de Moodle.'
            ]);
        }

        return redirect()->route('schedule.schedules.index')
            ->with('success', 'L\'élément a été supprimé de l\'ERP et de Moodle.');
    }

    public function getSchoolData($schoolId)
    {
        $classes = SchoolClass::where('school_id', $schoolId)->get()
            ->map(fn($c) => ['id' => $c->id, 'name' => $c->name]);

        $subjects = SchoolSubject::where('school_id', $schoolId)->get()
            ->map(fn($s) => ['id' => $s->id, 'name' => $s->custom_name ?? $s->name ?? ($s->subject->name ?? 'Matière #' . $s->id)]);

        $rooms = Room::where('school_id', $schoolId)->get()
            ->map(fn($r) => ['id' => $r->id, 'name' => $r->name ?? $r->title ?? 'Salle #' . $r->id]);

        $academicPeriods = AcademicPeriod::where('school_id', $schoolId)->get()
            ->map(fn($p) => ['id' => $p->id, 'name' => $p->name ?? $p->title ?? 'Période #' . $p->id]);

        $teachers = DB::table('staff_contracts')
            ->join('staff', 'staff_contracts.staff_id', '=', 'staff.id')
            ->join('personnes', 'staff.personne_id', '=', 'personnes.id')
            ->where('staff_contracts.school_id', $schoolId)
            ->select('staff.id', 'personnes.nom', 'personnes.prenoms')
            ->distinct()->get()
            ->map(fn($row) => [
                'id' => $row->id, 
                'name' => trim(($row->prenoms ?? '') . ' ' . ($row->nom ?? '')) ?: 'Enseignant #' . $row->id
            ]);

        return response()->json([
            'classes' => $classes, 'subjects' => $subjects, 'teachers' => $teachers,
            'rooms' => $rooms, 'academicPeriods' => $academicPeriods,
        ]);
    }
}