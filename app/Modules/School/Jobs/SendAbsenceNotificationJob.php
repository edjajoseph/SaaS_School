<?php

namespace App\Modules\School\Jobs;

use App\Modules\School\Models\StudentAttendance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendAbsenceNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public StudentAttendance $attendance) {}

    public function handle(): void
    {
        $registration = $this->attendance->registration;
        $student = $registration->student;
        $parentPhone = $student->parent_phone ?? null;

        if ($this->attendance->status === 'absent' && $parentPhone) {
            $message = "Alerte Présence : L'étudiant " . $student->personne->nom . " " . $student->personne->prenoms . 
                       " a été marqué absent au cours du " . $this->attendance->date;

            // Appel de l'API SMS (Infobip / PANEL.SMSING.APP / TPEcloud)
            // SmsService::send($parentPhone, $message);
        }
    }
}