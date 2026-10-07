<?php

namespace App\Modules\School\Http\Controllers\Web\Attendance;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\Schedule;
use App\Modules\School\Models\Staff;
use App\Modules\School\Models\StaffAttendance;
use App\Modules\School\Services\StaffAttendancePayrollService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TeacherScheduleAttendanceController extends Controller
{
    /**
     * Interface de pointage des cours (Enseignant & Administrateur)
     */
    public function dashboard(Request $request)
    {
        $user = auth()->user();
        
        $isAdmin = $user->hasRole('admin') || $user->is_admin || $user->can('manage-attendance');

        // Récupération des filtres
        $selectedDate = $request->input('date', Carbon::today()->format('Y-m-d'));
        $selectedStaffId = $request->input('staff_id');

        $carbonDate = Carbon::parse($selectedDate);

        // Détermination du jour de la semaine selon le format stocké en BDD
        // Remarque: Carbon isoDayOfWeek va de 1 (Lundi) à 7 (Dimanche)
        // Détermination du jour de la semaine (1 = Lundi, 7 = Dimanche)
        $dayOfWeekInt = $carbonDate->dayOfWeekIso; // Ou $carbonDate->isoDayOfWeek()
        $dayOfWeekString = strtolower($carbonDate->format('l')); // ex: 'friday'

        // Requête sur schedules en filtrant sur le jour de la semaine (numérique ou texte) et actif
        $query = Schedule::with(['subject', 'schoolClass', 'staff.personne'])
            ->where('is_active', true)
            ->where(function($q) use ($dayOfWeekInt, $dayOfWeekString) {
                $q->where('day_of_week', $dayOfWeekInt)
                  ->orWhere('day_of_week', $dayOfWeekString);
            });

        // Filtrage par enseignant si ce n'est pas un admin
        if (!$isAdmin) {
            $staff = $user->staff;
            if (!$staff) {
                return redirect()->back()->with('error', 'Profil enseignant non trouvé.');
            }
            $query->where('staff_id', $staff->id);
        } else {
            if ($selectedStaffId) {
                $query->where('staff_id', $selectedStaffId);
            }
        }

        $rawSchedules = $query->orderBy('start_time', 'asc')->get();

        $now = Carbon::now();
        $schedules = [];

        foreach ($rawSchedules as $schedule) {
            $startTime = Carbon::parse($selectedDate . ' ' . $schedule->start_time);
            $endTime = Carbon::parse($selectedDate . ' ' . $schedule->end_time);

            $checkInStart = (clone $startTime)->subHour();
            $checkInEnd = clone $endTime;
            $checkOutStart = clone $endTime;
            $checkOutEnd = (clone $endTime)->addHour();

            // Règle pour enseignant : si la fenêtre est dépassée pour la date du jour
            if (!$isAdmin && $selectedDate === Carbon::today()->format('Y-m-d') && $now->greaterThan($checkOutEnd)) {
                continue;
            }

            // Récupération du pointage basé sur staff_id, schedule_id et la date précise sélectionnée
            $attendance = StaffAttendance::where('staff_id', $schedule->staff_id)
                ->where('schedule_id', $schedule->id)
                ->where('date', $selectedDate)
                ->first();

            // Contrôle des accès
            if ($isAdmin) {
                $canCheckIn = true;
                $canCheckOut = true;
            } else {
                $canCheckIn = $now->between($checkInStart, $checkInEnd) && (!$attendance || !$attendance->check_in);
                $canCheckOut = $now->between($checkOutStart, $checkOutEnd) && ($attendance && $attendance->check_in && !$attendance->check_out);
            }

            $schedule->attendance = $attendance;
            $schedule->can_check_in = $canCheckIn;
            $schedule->can_check_out = $canCheckOut;
            $schedule->check_in_start = $checkInStart->format('H:i');
            $schedule->check_out_end = $checkOutEnd->format('H:i');

            $schedules[] = $schedule;
        }

        $teachers = $isAdmin ? Staff::with('personne')->get() : collect();

        return view('School::staff_attendance.teacher_dashboard', compact(
            'schedules',
            'now',
            'isAdmin',
            'selectedDate',
            'selectedStaffId',
            'teachers'
        ));
    }

    /**
     * Traitement du pointage
     */
    public function submitAttendance(Request $request, Schedule $schedule, StaffAttendancePayrollService $payrollService)
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole('admin') || $user->is_admin || $user->can('manage-attendance');

        $request->validate([
            'action' => 'required|in:check_in,check_out,admin_update',
            'date' => 'nullable|date',
            'manual_check_in' => 'nullable|date_format:H:i',
            'manual_check_out' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string|max:500',
        ]);

        $staffId = $schedule->staff_id;
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        $now = Carbon::now();

        $attendance = StaffAttendance::firstOrNew([
            'staff_id' => $staffId,
            'schedule_id' => $schedule->id,
            'date' => $date,
        ], [
            'school_id' => $schedule->school_id ?? 1,
            'verification_method' => $isAdmin ? 'manual' : 'mac_address',
        ]);

        // Mode Rattrapage Admin
        if ($isAdmin && $request->action === 'admin_update') {
            if ($request->filled('manual_check_in')) {
                $attendance->check_in = Carbon::parse($date . ' ' . $request->manual_check_in);
            }
            if ($request->filled('manual_check_out')) {
                $attendance->check_out = Carbon::parse($date . ' ' . $request->manual_check_out);
            }

            $attendance->verification_method = 'manual';
            $attendance->notes = $request->notes ?? ($attendance->notes . ' [Rattrapage manuel par Admin : ' . $user->name . ']');
            $attendance->save();

            if ($attendance->check_in && $attendance->check_out) {
                $payrollService->processAttendance($attendance);
            }

            return redirect()->back()->with('success', 'Pointage mis à jour avec succès.');
        }

        // Mode Standard
        $startTime = Carbon::parse($date . ' ' . $schedule->start_time);
        $endTime = Carbon::parse($date . ' ' . $schedule->end_time);

        if ($request->action === 'check_in') {
            $checkInStart = (clone $startTime)->subHour();
            
            if (!$isAdmin && !$now->between($checkInStart, $endTime)) {
                return redirect()->back()->with('error', 'Pointage d\'arrivée hors intervalle autorisé.');
            }

            $attendance->check_in = $now;
            $attendance->save();

            return redirect()->back()->with('success', 'Heure d\'arrivée enregistrée (' . $now->format('H:i') . ').');
        }

        if ($request->action === 'check_out') {
            $checkOutEnd = (clone $endTime)->addHour();

            if (!$isAdmin && !$now->between($endTime, $checkOutEnd)) {
                return redirect()->back()->with('error', 'Pointage de départ hors intervalle autorisé.');
            }

            $attendance->check_out = $now;
            $attendance->save();

            $payrollService->processAttendance($attendance);

            return redirect()->back()->with('success', 'Heure de départ enregistrée (' . $now->format('H:i') . ').');
        }
    }
}