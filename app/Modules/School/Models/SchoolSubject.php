<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SchoolSubject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'subject_id',
        'teaching_unit_id',
        'custom_name',
        'code',
        'color_code',
        'credits',
        'coefficient',
        'hours_cm',
        'hours_td',
        'hours_tp',
        'is_active',
    ];

    protected $casts = [
        'credits' => 'decimal:1',
        'coefficient' => 'decimal:2',
        'hours_cm' => 'integer',
        'hours_td' => 'integer',
        'hours_tp' => 'integer',
        'is_active' => 'boolean',
    ];

    
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    // Unité d'Enseignement parente (si modèle LMD)
    public function teachingUnit(): BelongsTo
    {
        return $this->belongsTo(TeachingUnit::class, 'teaching_unit_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    // Obtenir le volume horaire global de la matière
    public function getTotalHoursAttribute(): int
    {
        return $this->hours_cm + $this->hours_td + $this->hours_tp;
    }
}