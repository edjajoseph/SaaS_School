<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Level extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'levels';

    protected $fillable = [
        'cycle_id',
        'code',
        'name',
        'has_exam',
        'exam_name',
        'tuition_fee', // 👈 Ajout ici
        'sequence_order',
        'is_active',
    ];

    protected $casts = [
        'has_exam'       => 'boolean',
        'is_active'      => 'boolean',
        'sequence_order' => 'integer',
        'tuition_fee'    => 'float', // 👈 Ajout ici
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function cycle(): BelongsTo
    {
        return $this->belongsTo(Cycle::class);
    }

    public function getCycleNameAttribute(): string
    {
        return $this->cycle ? $this->cycle->name : 'Non défini';
    }

    public function classes(): HasMany
    {
        return $this->hasMany(SchoolClass::class);
    }

    public function students(): HasManyThrough
    {
        return $this->hasManyThrough(Student::class, SchoolClass::class);
    }
}