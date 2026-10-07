<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeacherSubjectRate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'staff_id',
        'school_class_id',
        'school_subject_id',
        'academic_period_id',
        'volume_cm',
        'volume_td',
        'volume_tp',
        'volume_examen',
        'executed_volume_cm',
        'executed_volume_td',
        'executed_volume_tp',
        'executed_volume_examen',
        'total_executed_hours',
        'rate_cm',
        'rate_td',
        'rate_tp',
        'rate_examen',
        'is_customized',
        'is_completed',
        'completed_at',
    ];

    protected $casts = [
        'volume_cm' => 'integer',
        'volume_td' => 'integer',
        'volume_tp' => 'integer',
        'volume_examen' => 'integer',
        'executed_volume_cm' => 'decimal:2',
        'executed_volume_td' => 'decimal:2',
        'executed_volume_tp' => 'decimal:2',
        'executed_volume_examen' => 'decimal:2',
        'total_executed_hours' => 'decimal:2',
        'rate_cm' => 'decimal:2',
        'rate_td' => 'decimal:2',
        'rate_tp' => 'decimal:2',
        'rate_examen' => 'decimal:2',
        'is_customized' => 'boolean',
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(SchoolSubject::class, 'school_subject_id');
    }

    /**
     * Total du volume horaire attribué.
     */
    public function getTotalVolumeAttribute(): int
    {
        return $this->volume_cm + $this->volume_td + $this->volume_tp + $this->volume_examen;
    }

    /**
     * Obtient le volume horaire total prévu (CM + TD + TP + Examen).
     */
    public function getTotalPlannedHoursAttribute(): float
    {
        return (float) ($this->volume_cm + $this->volume_td + $this->volume_tp + $this->volume_examen);
    }

    /**
     * Obtient le pourcentage global d'avancement de la matière.
     */
    public function getProgressPercentageAttribute(): float
    {
        $planned = $this->total_planned_hours;

        if ($planned <= 0) {
            return 0.0;
        }

        $percentage = ($this->total_executed_hours / $planned) * 100;

        return min(round($percentage, 2), 100.00);
    }

    /**
     * Recalcule les volumes exécutés et met à jour automatiquement le statut d'achèvement.
     * À appeler lors de la validation ou modification d'une entrée du cahier de texte.
     */
    public function updateExecutionProgress(string $sessionType, float $addedHours): void
    {
        switch (strtoupper($sessionType)) {
            case 'CM':
                $this->executed_volume_cm += $addedHours;
                break;
            case 'TD':
                $this->executed_volume_td += $addedHours;
                break;
            case 'TP':
                $this->executed_volume_tp += $addedHours;
                break;
            case 'EXAM':
            case 'EXAMEN':
            case 'CC':
                $this->executed_volume_examen += $addedHours;
                break;
        }

        // Recalcul du total exécuté
        $this->total_executed_hours = $this->executed_volume_cm 
            + $this->executed_volume_td 
            + $this->executed_volume_tp 
            + $this->executed_volume_examen;

        // Auto-vérification de l'achèvement
        if ($this->total_planned_hours > 0 && $this->total_executed_hours >= $this->total_planned_hours) {
            $this->is_completed = true;
            $this->completed_at = $this->completed_at ?? now();
        } else {
            $this->is_completed = false;
            $this->completed_at = null;
        }

        $this->save();
    }

    /**
     * Relation vers l'ensemble des fichiers/documents déposés.
     */
    public function materials(): HasMany
    {
        return $this->hasMany(CourseMaterial::class, 'teacher_subject_rate_id');
    }

    /**
     * Vérifie si au moins un support de cours est en ligne.
     */
    public function getHasMaterialsAttribute(): bool
    {
        return $this->materials()->exists();
    }
}