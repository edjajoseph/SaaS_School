<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'school_class_id',
        'school_subject_id',
        'evaluation_type_id',
        'academic_period_id',
        'title',
        'coefficient',
        'max_score',
        'evaluated_at',
        'is_published',
    ];

    protected $casts = [
        'coefficient' => 'decimal:2',
        'max_score' => 'decimal:2',
        'evaluated_at' => 'date',
        'is_published' => 'boolean',
    ];

    /* --- RELATIONS --- */

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    // Relation Système Classique (Matière)
    public function subject(): BelongsTo
    {
        return $this->belongsTo(SchoolSubject::class, 'school_subject_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(EvaluationType::class, 'evaluation_type_id');
    }

    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class, 'academic_period_id');
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    /* --- SCOPES --- */

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeLmd($query)
    {
        return $query->whereNotNull('ecue_id');
    }

    public function scopeClassique($query)
    {
        return $query->whereNotNull('school_subject_id');
    }

    /* --- HELPERS --- */

    public function isCatchUp(): bool
    {
        return $this->type ? $this->type->is_catch_up : false;
    }
}