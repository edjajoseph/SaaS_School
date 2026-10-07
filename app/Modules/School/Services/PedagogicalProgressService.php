<?php

namespace App\Modules\School\Services;

use App\Modules\School\Models\TeacherSubjectRate;
use App\Modules\School\Models\TeacherAttendance;
use App\Modules\School\Models\SchoolClass;
use App\Modules\School\Models\SchoolSubject;
use App\Modules\School\Models\Staff;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PedagogicalProgressService
{
    /**
     * Recalcule et persiste la progression horaire ainsi que le statut d'achèvement
     * dans la table teacher_subject_rates lors de la saisie ou modification d'une séance.
     *
     * @param int $classId
     * @param int $subjectId
     * @param int|null $staffId
     * @param bool $onlyValidated Prendre en compte uniquement les séances visées
     * @return TeacherSubjectRate|null
     */
    public function updateProgress(
        int $classId, 
        int $subjectId, 
        ?int $staffId = null, 
        bool $onlyValidated = false
    ): ?TeacherSubjectRate {
        // 1. Recherche du taux/volume horaire attribué à l'enseignant pour la classe et la matière
        $query = TeacherSubjectRate::query()
            ->where('school_class_id', $classId)
            ->where('school_subject_id', $subjectId);

        if ($staffId) {
            $query->where('staff_id', $staffId);
        }

        $rate = $query->first();

        if (!$rate) {
            return null;
        }

        // 2. Cumul des heures réalisées par type de séance dans le cahier de texte (TeacherAttendance)
        $attendanceQuery = TeacherAttendance::query()
            ->forClassAndSubject($classId, $subjectId)
            ->where('staff_id', $rate->staff_id);

        if ($onlyValidated) {
            $attendanceQuery->validated();
        }

        $hoursByType = $attendanceQuery
            ->select('session_type', DB::raw('SUM(hours_done) as total_hours'))
            ->groupBy('session_type')
            ->pluck('total_hours', 'session_type')
            ->toArray();

        $executedCm = (float) ($hoursByType['CM'] ?? 0);
        $executedTd = (float) ($hoursByType['TD'] ?? 0);
        $executedTp = (float) ($hoursByType['TP'] ?? 0);
        $executedExamen = (float) ($hoursByType['EXAMEN'] ?? $hoursByType['EXAM'] ?? $hoursByType['CC'] ?? 0);

        $totalExecutedHours = $executedCm + $executedTd + $executedTp + $executedExamen;
        $totalPlannedHours = (float) ($rate->volume_cm + $rate->volume_td + $rate->volume_tp + $rate->volume_examen);

        // 3. Auto-détermination du statut d'achèvement
        $isCompleted = ($totalPlannedHours > 0) && ($totalExecutedHours >= $totalPlannedHours);

        // 4. Mise à jour dans teacher_subject_rates
        $rate->update([
            'executed_volume_cm'     => $executedCm,
            'executed_volume_td'     => $executedTd,
            'executed_volume_tp'     => $executedTp,
            'executed_volume_examen' => $executedExamen,
            'total_executed_hours'   => $totalExecutedHours,
            'is_completed'           => $isCompleted,
            'completed_at'           => $isCompleted ? ($rate->completed_at ?? now()) : null,
        ]);

        return $rate;
    }

    /**
     * Consolide la progression pédagogique d'une matière pour une classe donnée.
     * Si $plannedHours n'est pas fourni (ou <= 0), il est extrait directement de teacher_subject_rates.
     *
     * @param int $classId
     * @param int $subjectId
     * @param float|null $plannedHours
     * @param bool $onlyValidated
     * @param int|null $staffId
     */
    public function getSubjectProgressForClass(
        int $classId, 
        int $subjectId, 
        ?float $plannedHours = null, 
        bool $onlyValidated = false,
        ?int $staffId = null
    ): array {
        // Charger la fiche de tarification/volume
        $rateQuery = TeacherSubjectRate::query()
            ->where('school_class_id', $classId)
            ->where('school_subject_id', $subjectId);

        if ($staffId) {
            $rateQuery->where('staff_id', $staffId);
        }

        $rate = $rateQuery->first();

        // Récupération automatique du volume horaire prévu s'il n'est pas passé en argument
        if (($plannedHours === null || $plannedHours <= 0) && $rate) {
            $plannedHours = (float) ($rate->volume_cm + $rate->volume_td + $rate->volume_tp + $rate->volume_examen);
        }

        $plannedHours = (float) ($plannedHours ?? 0);

        // Requête sur les émargements du cahier de textes
        $query = TeacherAttendance::query()
            ->forClassAndSubject($classId, $subjectId);

        if ($staffId) {
            $query->where('staff_id', $staffId);
        }

        if ($onlyValidated) {
            $query->validated();
        }

        // Cumul des heures effectuées par type de séance
        $hoursByType = (clone $query)
            ->select('session_type', DB::raw('SUM(hours_done) as total_hours'))
            ->groupBy('session_type')
            ->pluck('total_hours', 'session_type')
            ->toArray();

        $executedHours = (float) array_sum($hoursByType);
        $remainingHours = max(0, $plannedHours - $executedHours);
        $progressPercentage = $plannedHours > 0 
            ? min(100, round(($executedHours / $plannedHours) * 100, 2)) 
            : 0;

        $isCompleted = $rate ? $rate->is_completed : ($plannedHours > 0 && $executedHours >= $plannedHours);

        // Dernier chapitre abordé
        $lastAttendance = (clone $query)
            ->latest('date')
            ->latest('start_time')
            ->first();

        return [
            'school_class_id'     => $classId,
            'school_subject_id'   => $subjectId,
            'planned_hours'       => $plannedHours,
            'executed_hours'      => $executedHours,
            'remaining_hours'     => $remainingHours,
            'progress_percentage' => $progressPercentage,
            'is_completed'        => $isCompleted,
            'completed_at'        => $rate?->completed_at?->format('Y-m-d H:i'),
            'breakdown_by_type'   => [
                'CM'     => (float) ($hoursByType['CM'] ?? 0),
                'TD'     => (float) ($hoursByType['TD'] ?? 0),
                'TP'     => (float) ($hoursByType['TP'] ?? 0),
                'EXAMEN' => (float) ($hoursByType['EXAMEN'] ?? 0),
            ],
            'last_topic'          => $lastAttendance?->topic_covered,
            'last_chapter'        => $lastAttendance?->chapter_title,
            'last_session_date'   => $lastAttendance?->date?->format('Y-m-d'),
            'total_sessions'      => $query->count(),
            'rate_record'         => $rate,
        ];
    }

    /**
     * Consolide la progression globale de toutes les matières d'une classe.
     */
    public function getClassOverallProgress(int $classId, array $subjectPlannedHoursMap, bool $onlyValidated = false): Collection
    {
        return collect($subjectPlannedHoursMap)->map(function ($plannedHours, $subjectId) use ($classId, $onlyValidated) {
            return $this->getSubjectProgressForClass($classId, (int) $subjectId, (float) $plannedHours, $onlyValidated);
        });
    }

    /**
     * Obtenir le récapitulatif des heures effectuées par un enseignant (Volume horaire exécuté par contrat).
     */
    public function getStaffExecutionSummary(int $staffId, ?string $startDate = null, ?string $endDate = null): array
    {
        $query = TeacherAttendance::query()
            ->forStaff($staffId)
            ->with(['schoolClass', 'subject']);

        if ($startDate && $endDate) {
            $query->betweenDates($startDate, $endDate);
        }

        $attendances = $query->get();

        $totalHours = $attendances->sum('hours_done');
        $validatedHours = $attendances->where('is_validated', true)->sum('hours_done');
        $pendingHours = $attendances->where('is_validated', false)->sum('hours_done');

        // Groupement par matière et classe
        $summaryBySubject = $attendances->groupBy(fn ($item) => $item->school_class_id . '_' . $item->school_subject_id)
            ->map(function ($group) {
                $first = $group->first();
                return [
                    'class_name'      => $first->schoolClass?->name ?? 'N/A',
                    'subject_name'    => $first->subject?->name ?? 'N/A',
                    'total_hours'     => (float) $group->sum('hours_done'),
                    'validated_hours' => (float) $group->where('is_validated', true)->sum('hours_done'),
                    'session_count'   => $group->count(),
                ];
            })->values();

        return [
            'staff_id'         => $staffId,
            'total_hours'      => (float) $totalHours,
            'validated_hours'  => (float) $validatedHours,
            'pending_hours'    => (float) $pendingHours,
            'total_sessions'   => $attendances->count(),
            'by_subject_class' => $summaryBySubject,
        ];
    }

    /**
     * Valide massivement les émargements d'une classe pour une période donnée.
     */
    public function bulkValidateAttendances(array $attendanceIds, int $validatorUserId, ?string $notes = null): int
    {
        $updated = TeacherAttendance::query()
            ->whereIn('id', $attendanceIds)
            ->where('is_validated', false)
            ->update([
                'is_validated'     => true,
                'validated_by'     => $validatorUserId,
                'validated_at'     => now(),
                'validation_notes' => $notes,
            ]);

        // Optionnel : Recalculer les progressions des matières impactées si le calcul se basait uniquement sur les séances validées
        return $updated;
    }
}