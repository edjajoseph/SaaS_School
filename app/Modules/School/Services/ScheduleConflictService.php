<?php

namespace App\Modules\School\Services;

use App\Modules\School\Models\Schedule;

class ScheduleConflictService
{
    /**
     * Vérifie la présence de conflits de salle, d'enseignant ou de classe.
     *
     * @param array $data Contient: day_of_week, start_time, end_time, room_id, staff_id, school_class_id
     * @param int|null $ignoreScheduleId ID de la séance à ignorer (en cas de modification)
     * @return array Tableau contenant les messages d'erreur de conflit
     */
    public static function checkConflicts(array $data, ?int $ignoreScheduleId = null): array
    {
        $errors = [];

        $day = $data['day_of_week'];
        $startTime = $data['start_time'];
        $endTime = $data['end_time'];

        // Requête de base : séances ACTIVES, NON TERMINÉES et du même jour qui se chevauchent
        $overlappingQuery = Schedule::where('day_of_week', $day)
            ->where('is_active', true)
            ->where('is_completed', false)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where('start_time', '<', $endTime)
                      ->where('end_time', '>', $startTime);
            })
            ->when(isset($data['academic_period_id']), function ($query) use ($data) {
                $query->where('academic_period_id', $data['academic_period_id']);
            })
            ->when($ignoreScheduleId, function ($query) use ($ignoreScheduleId) {
                $query->where('id', '!=', $ignoreScheduleId);
            });

        // 1. Conflit de Salle
        if (!empty($data['room_id'])) {
            $roomConflict = (clone $overlappingQuery)
                ->where('room_id', $data['room_id'])
                ->with(['room', 'schoolClass'])
                ->first();

            if ($roomConflict) {
                $roomName = $roomConflict->room->name ?? $roomConflict->room->title ?? 'N/A';
                $className = $roomConflict->schoolClass->name ?? 'N/A';
                $errors[] = "Conflit de Salle : La salle [{$roomName}] est déjà occupée par la classe [{$className}] de {$roomConflict->start_time} à {$roomConflict->end_time}.";
            }
        }

        // 2. Conflit d'Enseignant
        if (!empty($data['staff_id'])) {
            $teacherConflict = (clone $overlappingQuery)
                ->where('staff_id', $data['staff_id'])
                ->with(['teacher.personne', 'schoolClass'])
                ->first();

            if ($teacherConflict) {
                $nom = $teacherConflict->teacher->personne->nom ?? '';
                $prenoms = $teacherConflict->teacher->personne->prenoms ?? '';
                $teacherName = trim("{$nom} {$prenoms}") ?: 'l\'enseignant';
                $className = $teacherConflict->schoolClass->name ?? 'N/A';
                $cStart = $teacherConflict->start_time;
                $cEnd = $teacherConflict->end_time;
                
                $errors[] = "Conflit d'Enseignant : {$teacherName} a déjà un cours programmé avec la classe [{$className}] de {$cStart} à {$cEnd}.";
            }
        }

        // 3. Conflit de Classe
        if (!empty($data['school_class_id'])) {
            $classConflict = (clone $overlappingQuery)
                ->where('school_class_id', $data['school_class_id'])
                ->with(['schoolClass', 'subject'])
                ->first();

            if ($classConflict) {
                $className = $classConflict->schoolClass->name ?? 'N/A';
                $subjectName = $classConflict->subject->custom_name ?? $classConflict->subject->name ?? 'Matière';
                $errors[] = "Conflit de Classe : La classe [{$className}] a déjà le cours de [{$subjectName}] programmé de {$classConflict->start_time} à {$classConflict->end_time}.";
            }
        }

        return $errors;
    }
}