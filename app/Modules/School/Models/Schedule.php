<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Schedule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'school_class_id',
        'school_subject_id',
        'staff_id',
        'room_id',
        'academic_period_id',
        'session_type',
        'day_of_week',
        'start_time',
        'end_time',
        'moodle_event_id',
        'is_active',    // true: cours planifié/actif, false: suspendu/désactivé
        'is_completed', // true: ECUE/volume horaire achevé (permet de libérer le créneau)
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_completed' => 'boolean',
    ];

    /**
     * Scope pour filtrer les créneaux qui se chevauchent sur le même jour.
     */
    public function scopeOverlapping(Builder $query, int $dayOfWeek, string $startTime, string $endTime, ?int $ignoreId = null)
    {
        return $query->where('is_active', true)
            ->where('is_completed', false)
            ->where('day_of_week', $dayOfWeek)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->where(function ($sub) use ($startTime, $endTime) {
                    $sub->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $startTime);
                });
            })
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId));
    }

    // Relations
    public function school(): BelongsTo { return $this->belongsTo(School::class); }
    public function schoolClass(): BelongsTo { return $this->belongsTo(SchoolClass::class, 'school_class_id'); }
    public function subject(): BelongsTo { return $this->belongsTo(SchoolSubject::class, 'school_subject_id'); }
    public function teacher(): BelongsTo { return $this->belongsTo(Staff::class, 'staff_id'); }
    public function room(): BelongsTo { return $this->belongsTo(Room::class); }
    public function academicPeriod(): BelongsTo { return $this->belongsTo(AcademicPeriod::class, 'academic_period_id'); }
}