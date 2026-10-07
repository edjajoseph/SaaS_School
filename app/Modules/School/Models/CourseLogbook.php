<?php

namespace App\Modules\School\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseLogbook extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'schedule_id',
        'staff_id',
        'school_class_id',
        'school_subject_id',
        'academic_period_id',
        'entry_date',
        'start_time',
        'end_time',
        'duration_hours',
        'chapter_title',
        'objectives',
        'summary',
        'homework',
        'homework_due_date',
        'is_validated',
        'validated_by',
        'validated_at',
        'validation_notes',
    ];

    protected $casts = [
        'entry_date'        => 'date',
        'homework_due_date' => 'date',
        'validated_at'      => 'datetime',
        'is_validated'      => 'boolean',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(SchoolSubject::class, 'school_subject_id');
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }
}