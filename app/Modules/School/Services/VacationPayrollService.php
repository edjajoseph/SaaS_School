<?php

namespace App\Modules\School\Services;

use App\Modules\School\Models\Staff;
use App\Modules\School\Models\HourlyRate;
use App\Modules\School\Models\Schedule;
use Carbon\Carbon;

class VacationPayrollService
{
    /**
     * Calcule la paie d'un enseignant vacataire sur une période donnée
     */
    public function calculateStaffPayroll(Staff $staff, string $startDate, string $endDate)
    {
        $hourlyRate = HourlyRate::where('staff_id', $staff->id)->first();
        
        if (!$hourlyRate) {
            return [
                'total_hours' => 0,
                'total_amount' => 0,
                'details' => [],
            ];
        }

        // Récupérer les créneaux effectués par l'enseignant
        $schedules = Schedule::with(['subject', 'schoolClass'])
            ->where('staff_id', $staff->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $totalCM = 0;
        $totalTD = 0;
        $totalTP = 0;

        foreach ($schedules as $schedule) {
            // Calcul de la durée du créneau en heures (ex: 8h30 à 11h30 = 3h)
            $start = Carbon::parse($schedule->start_time);
            $end = Carbon::parse($schedule->end_time);
            $hours = $end->diffInMinutes($start) / 60;

            // Ventilation selon le type de séance (CM, TD, TP)
            match (strtoupper($schedule->session_type ?? 'CM')) {
                'TD' => $totalTD += $hours,
                'TP' => $totalTP += $hours,
                default => $totalCM += $hours,
            };
        }

        $amountCM = $totalCM * $hourlyRate->rate_cm;
        $amountTD = $totalTD * $hourlyRate->rate_td;
        $amountTP = $totalTP * $hourlyRate->rate_tp;

        return [
            'staff' => $staff,
            'period' => ['start' => $startDate, 'end' => $endDate],
            'hours' => [
                'cm' => $totalCM,
                'td' => $totalTD,
                'tp' => $totalTP,
                'total' => $totalCM + $totalTD + $totalTP,
            ],
            'amounts' => [
                'cm' => $amountCM,
                'td' => $amountTD,
                'tp' => $amountTP,
                'grand_total' => $amountCM + $amountTD + $amountTP,
            ],
        ];
    }
}