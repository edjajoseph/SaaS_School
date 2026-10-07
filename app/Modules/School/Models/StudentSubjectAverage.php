<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentSubjectAverage extends Model
{
    protected $fillable = [
        'subject_class_average_id',
        'registration_id',
        'average',
        'rank',
        'rank_formatted',
        'absences_count',
        'is_exempted',
        'remarks',
    ];

    protected $casts = [
        'average' => 'decimal:2',
        'is_exempted' => 'boolean',
    ];

    public function classAverage(): BelongsTo
    {
        return $this->belongsTo(SubjectClassAverage::class, 'subject_class_average_id');
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class);
    }
}