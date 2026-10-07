<?php

namespace App\Modules\School\Http\Controllers\Web\Attendance;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\Schedule;
use App\Modules\School\Models\SchoolClass;
use App\Modules\School\Models\Registration;
use App\Modules\School\Models\StudentAttendance;
use App\Modules\School\Jobs\SendAbsenceNotificationJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AttendanceController extends Controller
{
    /**
     * Page de sélection des créneaux pour le pointage
     */
    public function index(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $classId = $request->input('school_class_id');

        $classes = SchoolClass::all();

        // Récupérer les créneaux (schedules) selon la date et/ou la classe
        $query = Schedule::with(['subject', 'schoolClass', 'teacher.personne']);

        if ($classId) {
            $query->where('school_class_id', $classId);
        }

        $schedules = $query->get();

        return view('School::attendance.index', compact('schedules', 'classes', 'date', 'classId'));
    }
    
    /**
     * Interface de pointage pour un créneau (Schedule) et une date donnés
     */
    public function takeAttendance(Request $request, Schedule $schedule)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $schedule->load(['schoolClass', 'subject', 'teacher.personne']);

        // Récupération des étudiants inscrits dans la classe associée au créneau
        $registrations = Registration::with('student.personne')
            ->where('school_class_id', $schedule->school_class_id)
            // Ne filtrez sur l'année académique que si elle est définie sur le schedule
            ->when($schedule->academic_year_id, function ($query) use ($schedule) {
                return $query->where('academic_year_id', $schedule->academic_year_id);
            })
            // Retirez ou adaptez le filtre sur le statut si vos inscriptions utilisent d'autres valeurs ('active', 'inscript', etc.)
            ->whereIn('status', ['confirmed', 'active', 'validated', 'inscript'])
            ->get();

        // Récupération des pointages existants
        $existingAttendances = StudentAttendance::where('schedule_id', $schedule->id)
            ->where('date', $date)
            ->get()
            ->keyBy('registration_id');

        return view('School::attendance.take', compact('schedule', 'date', 'registrations', 'existingAttendances'));
    }

    /**
     * Enregistrement/Mise à jour massive des présences
     */
    public function storeAttendance(Request $request, Schedule $schedule)
    {
        $request->validate([
            'date' => 'required|date',
            'attendances' => 'required|array',
            'attendances.*.registration_id' => 'required|exists:registrations,id',
            'attendances.*.status' => 'required|in:present,absent,late,excused',
            'attendances.*.late_minutes' => 'nullable|integer|min:0',
            'attendances.*.reason' => 'nullable|string|max:255',
        ]);

        $date = $request->input('date');

        DB::transaction(function () use ($request, $schedule, $date) {
            foreach ($request->attendances as $data) {
                $attendance = StudentAttendance::updateOrCreate(
                    [
                        'schedule_id' => $schedule->id,
                        'registration_id' => $data['registration_id'],
                        'date' => $date,
                    ],
                    [
                        'status' => $data['status'],
                        'late_minutes' => $data['status'] === 'late' ? ($data['late_minutes'] ?? 0) : 0,
                        'reason' => $data['reason'] ?? null,
                    ]
                );

                // Déclenchement de la notification en tâche de fond en cas d'absence non justifiée
                if ($attendance->status === 'absent') {
                    dispatch(new SendAbsenceNotificationJob($attendance));
                }
            }
        });

        return redirect()->back()->with('success', 'Feuille de présence enregistrée avec succès.');
    }

    /**
     * Génération de la fiche d'émargement / présence au format PDF
     */
    public function printAttendance(Request $request, Schedule $schedule)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $schedule->load(['schoolClass.school', 'subject', 'teacher.personne']);

        $registrations = Registration::with('student.personne')
            ->where('school_class_id', $schedule->school_class_id)
            ->when($schedule->academic_year_id, function ($query) use ($schedule) {
                return $query->where('academic_year_id', $schedule->academic_year_id);
            })
            ->get();

        $existingAttendances = StudentAttendance::where('schedule_id', $schedule->id)
            ->where('date', $date)
            ->get()
            ->keyBy('registration_id');

        $pdf = Pdf::loadView('School::attendance.pdf_attendance', compact('schedule', 'date', 'registrations', 'existingAttendances'))
                ->setPaper('a4', 'portrait');

        return $pdf->stream('Fiche_Presence_' . ($schedule->subject->name ?? 'Cours') . '_' . $date . '.pdf');
    }

}