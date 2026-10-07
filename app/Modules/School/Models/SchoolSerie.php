<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SchoolSerie extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'school_series';

    protected $fillable = [
        'school_id',
        'serie_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relation avec l'établissement scolaire
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Relation avec la série (ex: A, C, D, etc.)
     */
    public function serie(): BelongsTo
    {
        return $this->belongsTo(Serie::class);
    }
}