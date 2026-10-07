<?php

namespace App\Modules\School\Services;

use App\Modules\School\Models\LeaveRequest;
use App\Modules\School\Notifications\LeaveRequestStatusUpdated;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LeaveRequestService
{
    /**
     * Traiter et valider/rejeter une demande
     */
    public function processRequest(LeaveRequest $request, string $action, ?string $rejectionReason, $validatorUser): bool
    {
        if (!in_array($action, ['approve', 'reject'])) {
            return false;
        }

        $status = ($action === 'approve') ? 'approved' : 'rejected';

        DB::transaction(function () use ($request, $status, $validatorUser, $rejectionReason) {
            $request->update([
                'status'           => $status,
                'processed_by'     => $validatorUser->id,
                'processed_at'     => now(),
                'rejection_reason' => $status === 'rejected' ? $rejectionReason : null,
            ]);

            // Si approuvé, synchroniser avec la présence des enseignants
            if ($status === 'approved') {
                $this->markTeacherAttendanceAsJustified($request);
            }

            // Notification à l'utilisateur
            $request->user->notify(new LeaveRequestStatusUpdated($request));
        });

        return true;
    }

    /**
     * Marquer les cours pendant cette période comme "Absence Justifiée (Autorisation)"
     */
    private function markTeacherAttendanceAsJustified(LeaveRequest $request)
    {
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        // Récupérer les créneaux horaire (schedules) de l'enseignant dans cette école
        $schedules = DB::table('schedules')
            ->where('staff_id', $request->user_id)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->get();

        // Parcourir chaque jour de la plage de congés/permissions
        $period = \Carbon\CarbonPeriod::create($startDate->toDateString(), $endDate->toDateString());

        foreach ($period as $date) {
            $dayOfWeekIso = $date->dayOfWeekIso; // 1 = Lundi, 7 = Dimanche

            foreach ($schedules as $schedule) {
                if ($schedule->day_of_week == $dayOfWeekIso) {
                    $sessionStart = Carbon::parse($date->toDateString() . ' ' . $schedule->start_time);
                    $sessionEnd   = Carbon::parse($date->toDateString() . ' ' . $schedule->end_time);

                    // Vérifier s'il y a un chevauchement entre le cours et l'autorisation
                    if ($startDate < $sessionEnd && $endDate > $sessionStart) {
                        
                        // Mettre à jour ou insérer dans teacher_attendances
                        DB::table('teacher_attendances')->updateOrInsert(
                            [
                                'schedule_id' => $schedule->id,
                                'date'        => $date->toDateString(),
                            ],
                            [
                                'staff_id'      => $request->user_id,
                                'status'        => 'absent_justified',
                                'remark'        => 'Absence Justifiée (Autorisation Accordée #' . $request->id . ')',
                                'hours_done'    => 0,
                                'updated_at'    => now(),
                                'created_at'    => now(),
                            ]
                        );
                    }
                }
            }
        }
    }
}