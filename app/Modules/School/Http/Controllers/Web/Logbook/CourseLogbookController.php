<?php

namespace App\Modules\School\Http\Controllers\Web\Logbook;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\CourseLogbook;
use App\Modules\School\Models\Schedule;
use App\Modules\School\Models\SchoolClass;
use App\Modules\School\Models\SchoolSubject;
use App\Modules\School\Models\SchoolSubjectProgress;
use App\Modules\School\Services\PedagogicalProgressService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CourseLogbookController extends Controller
{
    protected PedagogicalProgressService $progressService;

    public function __construct(PedagogicalProgressService $progressService)
    {
        $this->progressService = $progressService;
    }

    /**
     * Affichage du cahier de textes (Filtrable par classe, matière et enseignant)
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $isAdminOrDirector = $user->hasRole('admin') || $user->hasRole('directeur_etudes') || $user->can('validate-logbook');

        $selectedClassId = $request->input('school_class_id');
        $selectedSubjectId = $request->input('school_subject_id');

        $query = CourseLogbook::with(['staff.personne', 'schoolClass', 'subject', 'validator'])
            ->orderBy('entry_date', 'desc')
            ->orderBy('start_time', 'desc');

        // Si c'est un enseignant, limiter à ses propres enseignements
        if (!$isAdminOrDirector && $user->staff) {
            $query->where('staff_id', $user->staff->id);
        }

        if ($selectedClassId) {
            $query->where('school_class_id', $selectedClassId);
        }

        if ($selectedSubjectId) {
            $query->where('school_subject_id', $selectedSubjectId);
        }

        $entries = $query->paginate(15);
        $classes = SchoolClass::where('is_active', true)->get();
        $subjects = SchoolSubject::all();

        // Récupération de la progression pédagogique si une classe et une matière sont sélectionnées
        $progress = null;
        if ($selectedClassId && $selectedSubjectId) {
            $progress = SchoolSubjectProgress::where('school_class_id', $selectedClassId)
                ->where('school_subject_id', $selectedSubjectId)
                ->first();
        }

        return view('School::logbook.index', compact(
            'entries',
            'classes',
            'subjects',
            'selectedClassId',
            'selectedSubjectId',
            'progress',
            'isAdminOrDirector'
        ));
    }

    /**
     * Formulaire de saisie du cahier de textes (Enseignant)
     */
    public function create(Request $request)
    {
        $scheduleId = $request->input('schedule_id');
        $schedule = $scheduleId ? Schedule::with(['schoolClass', 'subject', 'staff'])->find($scheduleId) : null;

        $classes = SchoolClass::where('is_active', true)->get();
        $subjects = SchoolSubject::all();

        return view('School::logbook.create', compact('schedule', 'classes', 'subjects'));
    }

    /**
     * Enregistrement d'une séance dans le cahier de textes
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_class_id'   => 'required|exists:school_classes,id',
            'school_subject_id' => 'required|exists:school_subjects,id',
            'schedule_id'       => 'nullable|exists:schedules,id',
            'entry_date'        => 'required|date',
            'start_time'        => 'required|date_format:H:i',
            'end_time'          => 'required|date_format:H:i|after:start_time',
            'chapter_title'     => 'nullable|string|max:255',
            'objectives'        => 'nullable|string',
            'summary'           => 'required|string',
            'homework'          => 'nullable|string',
            'homework_due_date' => 'nullable|date|after_or_equal:entry_date',
        ]);

        $user = auth()->user();
        $staffId = $user->staff->id ?? $request->input('staff_id');

        // Calcul de la durée en heures
        $start = Carbon::parse($validated['entry_date'] . ' ' . $validated['start_time']);
        $end = Carbon::parse($validated['entry_date'] . ' ' . $validated['end_time']);
        $duration = round($start::diffInMinutes($end) / 60, 2);

        $logbook = CourseLogbook::create([
            'school_id'          => $user->school_id ?? 1,
            'schedule_id'        => $validated['schedule_id'] ?? null,
            'staff_id'           => $staffId,
            'school_class_id'    => $validated['school_class_id'],
            'school_subject_id'  => $validated['school_subject_id'],
            'entry_date'         => $validated['entry_date'],
            'start_time'         => $validated['start_time'],
            'end_time'           => $validated['end_time'],
            'duration_hours'     => $duration,
            'chapter_title'      => $validated['chapter_title'] ?? null,
            'objectives'         => $validated['objectives'] ?? null,
            'summary'            => $validated['summary'],
            'homework'           => $validated['homework'] ?? null,
            'homework_due_date'  => $validated['homework_due_date'] ?? null,
        ]);

        // Mise à jour automatique de la progression pédagogique
        $this->progressService->updateProgress($validated['school_class_id'], $validated['school_subject_id']);

        return redirect()->route('school.logbook.index')->with('success', 'Séance enregistrée avec succès dans le cahier de textes.');
    }

    /**
     * Visa / Validation administrative par le Directeur des Études
     */
    public function validateEntry(Request $request, CourseLogbook $logbook)
    {
        $user = auth()->user();

        $request->validate([
            'validation_notes' => 'nullable|string|max:500',
        ]);

        $logbook->update([
            'is_validated'     => true,
            'validated_by'     => $user->id,
            'validated_at'     => Carbon::now(),
            'validation_notes' => $request->input('validation_notes'),
        ]);

        return redirect()->back()->with('success', 'Le cahier de textes a été visé et validé avec succès.');
    }
}