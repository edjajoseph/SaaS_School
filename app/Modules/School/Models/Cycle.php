<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cycle extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cycles';

    protected $fillable = [
        'code',
        'name',
        'sequence_order',
        'description',
        'is_active',
    ];

    protected $casts = [
        'sequence_order' => 'integer',
        'is_active'      => 'boolean',
    ];

    /**
     * Un cycle contient plusieurs niveaux (Ex: Cycle Primaire -> CP1, CP2, CE1, etc.)
     */
    public function levels()
    {
        return $this->hasMany(Level::class)->orderBy('sequence_order');
    }

    /**
     * Scope pour récupérer uniquement les cycles actifs.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}