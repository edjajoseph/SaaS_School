<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseMaterial extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'teacher_subject_rate_id',
        'title',
        'type',
        'file_path',
        'original_filename',
        'mime_type',
        'file_size',
        'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function teacherSubjectRate(): BelongsTo
    {
        return $this->belongsTo(TeacherSubjectRate::class, 'teacher_subject_rate_id');
    }
}