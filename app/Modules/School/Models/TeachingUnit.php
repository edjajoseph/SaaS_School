<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TeachingUnit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'level_id',
        'serie_id',
        'name',
        'code',
        'type',
        'credits',
        'coefficient',
        'is_active',
    ];

    protected $casts = [
        'credits' => 'integer',
        'coefficient' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function serie(): BelongsTo
    {
        return $this->belongsTo(Serie::class);
    }

    // Les ECUE / Matières composant cette UE
    public function schoolsubjects(): HasMany
    {
        return $this->hasMany(SchoolSubject::class);
    }

    // Calcul du volume horaire total de l'UE
    public function getTotalHoursAttribute(): int
    {
        return $this->schoolsubjects->sum(function ($schoolsubject) {
            return $schoolsubject->hours_cm + $schoolsubject->hours_td + $schoolsubject->hours_tp;
        });
    }
}