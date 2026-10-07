<?php

namespace App\Modules\School\Services;

use App\Modules\School\Models\Staff;
use App\Modules\School\Models\SchoolClass;
use App\Modules\School\Models\TeacherSubjectRate;
use App\Modules\School\Models\HourlyRate;

class CourseAssignmentService
{
    /**
     * Attribue un cours à un enseignant, configure les taux et les volumes horaires,
     * tout en garantissant l'absence de doublons.
     *
     * @param Staff $staff Enseignant concerné
     * @param SchoolClass $class Classe attribuée
     * @param int $subjectId Identifiant du cours / matière (SchoolSubject)
     * @param int|null $academicPeriodId Identifiant de la période académique
     * @param array $volumes Volumes horaires prévus ['volume_cm' => 0, 'volume_td' => 0, ...]
     * @return TeacherSubjectRate
     */
    public function assignTeacherToSubject(
        Staff $staff, 
        SchoolClass $class, 
        int $subjectId, 
        ?int $academicPeriodId = null,
        array $volumes = []
    ): TeacherSubjectRate {
        // 1. Recherche du tarif par défaut dans la grille globale
        $defaultGrid = HourlyRate::where('school_id', $class->school_id)
            ->where('degree_id', $staff->degree_id)
            ->where('level_id', $class->level_id)
            ->where(function ($q) use ($class) {
                $q->where('series_id', $class->series_id)->orWhereNull('series_id');
            })
            ->first();

        if (!$defaultGrid) {
            $defaultGrid = HourlyRate::where('school_id', $class->school_id)
                ->where('degree_id', $staff->degree_id)
                ->whereNull('level_id')
                ->first();
        }

        // 2. Vérifier si l'attribution existe déjà
        $existingRate = TeacherSubjectRate::where([
            'school_id'          => $class->school_id,
            'staff_id'           => $staff->id,
            'school_class_id'    => $class->id,
            'school_subject_id'  => $subjectId,
            'academic_period_id' => $academicPeriodId,
        ])->first();

        // Si l'attribution existe déjà, met à jour les volumes si renseignés
        if ($existingRate) {
            $updateData = [];

            if (isset($volumes['volume_cm']))     $updateData['volume_cm']     = $volumes['volume_cm'];
            if (isset($volumes['volume_td']))     $updateData['volume_td']     = $volumes['volume_td'];
            if (isset($volumes['volume_tp']))     $updateData['volume_tp']     = $volumes['volume_tp'];
            if (isset($volumes['volume_examen'])) $updateData['volume_examen'] = $volumes['volume_examen'];

            if (!empty($updateData)) {
                $existingRate->update($updateData);
            }

            return $existingRate;
        }

        // 3. Création de l'attribution avec taux et volumes horaires
        return TeacherSubjectRate::create([
            'school_id'          => $class->school_id,
            'staff_id'           => $staff->id,
            'school_class_id'    => $class->id,
            'school_subject_id'  => $subjectId,
            'academic_period_id' => $academicPeriodId,

            // Volumes horaires prévus (défaut à 0 si non fournis)
            'volume_cm'          => $volumes['volume_cm'] ?? 0,
            'volume_td'          => $volumes['volume_td'] ?? 0,
            'volume_tp'          => $volumes['volume_tp'] ?? 0,
            'volume_examen'      => $volumes['volume_examen'] ?? 0,

            // Taux horaires appliqués (issus de la grille globale par défaut)
            'rate_cm'            => $defaultGrid->rate_cm ?? 0,
            'rate_td'            => $defaultGrid->rate_td ?? 0,
            'rate_tp'            => $defaultGrid->rate_tp ?? 0,
            'rate_examen'        => $defaultGrid->rate_examen ?? 0,
            
            'is_customized'      => false,
        ]);
    }
}