<?php

namespace App\Modules\School\Models;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherAttendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'teacher_attendances';

    protected $fillable = [
        'school_id',
        'staff_id',
        'school_class_id',
        'school_subject_id',
        'schedule_id',
        'academic_period_id',
        'date',
        'start_time',
        'end_time',
        'hours_done',
        'session_type',
        'chapter_title',
        'topic_covered',
        'objectives',
        'homework',
        'homework_due_date',
        'is_validated',
        'validated_by',
        'validated_at',
        'validation_notes',
    ];

    protected $casts = [
        'date'              => 'date',
        'homework_due_date' => 'date',
        'validated_at'      => 'datetime',
        'is_validated'      => 'boolean',
        'hours_done'        => 'decimal:2',
    ];

    /* =========================================================================
     | RELATIONS ELOQUENT
     | ========================================================================= */

    /**
     * Enseignant / Personnel vacataire concerné.
     */
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    /**
     * Classe / Promotion concernée par le cours.
     */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    /**
     * Matière / UE enseignée.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(SchoolSubject::class, 'school_subject_id');
    }

    /**
     * Créneau horaire d'origine dans l'emploi du temps (facultatif si cours imprévu).
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    /**
     * Période académique (Semestre / Trimestre).
     */
    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class, 'academic_period_id');
    }

    /**
     * Utilisateur (Directeur des Études / Chef de Dépt) ayant apposé le visa.
     */
    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    /* =========================================================================
     | SCOPES DE REQUÊTE (FILTRES PRATIQUES)
     | ========================================================================= */

    /**
     * Filtrer par plage de dates.
     */
    public function scopeBetweenDates(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Filtrer les séances validées (visées).
     */
    public function scopeValidated(Builder $query): Builder
    {
        return $query->where('is_validated', true);
    }

    /**
     * Filtrer les séances en attente de validation.
     */
    public function scopePendingValidation(Builder $query): Builder
    {
        return $query->where('is_validated', false);
    }

    /**
     * Filtrer par enseignant.
     */
    public function scopeForStaff(Builder $query, int $staffId): Builder
    {
        return $query->where('staff_id', $staffId);
    }

    /**
     * Filtrer par classe et matière.
     */
    public function scopeForClassAndSubject(Builder $query, int $classId, int $subjectId): Builder
    {
        return $query->where('school_class_id', $classId)
                     ->where('school_subject_id', $subjectId);
    }

    /* =========================================================================
     | MÉTHODES ET HELPERS DE GESTION
     | ========================================================================= */

    /**
     * Calcule automatiquement la durée en heures entre l'heure de début et de fin.
     */
    public function calculateHoursDone(): float
    {
        if (!$this->start_time || !$this->end_time) {
            return 0.0;
        }

        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);

        return round($start->diffInMinutes($end) / 60, 2);
    }

    /**
     * Appose le visa administratif sur la séance.
     */
    public function markAsValidated(int $validatorUserId, ?string $notes = null): bool
    {
        return $this->update([
            'is_validated'     => true,
            'validated_by'     => $validatorUserId,
            'validated_at'     => Carbon::now(),
            'validation_notes' => $notes,
        ]);
    }

    /**
     * Annule la validation administrative.
     */
    public function revokeValidation(): bool
    {
        return $this->update([
            'is_validated'     => false,
            'validated_by'     => null,
            'validated_at'     => null,
            'validation_notes' => null,
        ]);
    }

    /**
     * Vérifie si la séance contient des devoirs à rendre.
     */
    public function hasHomework(): bool
    {
        return !empty($this->homework);
    }
}