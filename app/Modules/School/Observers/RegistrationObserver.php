<?php

namespace App\Modules\School\Observers;

use App\Modules\School\Models\Registration;
use App\Modules\School\Models\Student;

class RegistrationObserver
{
    /**
     * Gère la création ou la mise à jour d'une inscription
     */
    public function saved(Registration $registration): void
    {
        $student = $registration->student;

        if (!$student) {
            return;
        }

        // Si l'inscription est confirmée, l'étudiant passe en mode actif
        if ($registration->status === 'confirmed') {
            $student->update(['status' => 'active']);
        } 
        // Si l'inscription bascule en attente, annulée ou transférée
        elseif (in_array($registration->status, ['pending', 'canceled', 'transferred'])) {
            // On vérifie s'il n'a pas une AUTRE inscription confirmée pour l'année courante
            $hasOtherConfirmedRegistration = Registration::where('student_id', $student->id)
                ->where('academic_year_id', $registration->academic_year_id)
                ->where('id', '!=', $registration->id)
                ->where('status', 'confirmed')
                ->exists();

            if (!$hasOtherConfirmedRegistration) {
                $student->update(['status' => 'inactive']);
            }
        }
    }
}