<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubjectClassAverage extends Model
{
    protected $fillable = [
        'school_id',
        'school_class_id',
        'academic_period_id',
        'school_subject_id',
        'class_average',
        'max_average',
        'min_average',
        'total_students',
        'passed_count',
        'calculated_at',
    ];

    protected $casts = [
        'class_average' => 'decimal:2',
        'max_average' => 'decimal:2',
        'min_average' => 'decimal:2',
        'calculated_at' => 'datetime',
    ];

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(SchoolSubject::class, 'school_subject_id');
    }

    public function studentAverages(): HasMany
    {
        return $this->hasMany(StudentSubjectAverage::class, 'subject_class_average_id');
    }
}