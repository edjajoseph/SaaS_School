<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//  AJOUTER l'importation officielle d'Eloquent :
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'schools';

    protected $fillable = [
        'current_academic_year_id',
        'name',
        'code',
        'official_approval_number',
        'logo_path',
        'stamp_path',
        'email',
        'phone_1',
        'phone_2',
        'po_box',
        'country',
        'city',
        'municipality',
        'address',
        'status',
        'website',
        'document_header',
        'document_footer',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Relation vers l'année académique courante (contexte de travail actif).
     */
    public function currentAcademicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class, 'current_academic_year_id');
    }

    /**
     * Toutes les années académiques associées à cet établissement.
     */
    public function academicYears(): HasManyThrough
{
    return $this->hasManyThrough(
        AcademicYear::class,
        AcademicPeriod::class,
        'school_id',        // Clé étrangère sur la table academic_periods
        'id',               // Clé primaire sur la table academic_years
        'id',               // Clé primaire sur la table schools
        'academic_year_id'  // Clé étrangère sur la table academic_periods
    )->distinct();
}

    /**
     * Les classes rattachées à cet établissement.
     */
    public function classes(): HasMany
    {
        return $this->hasMany(SchoolClass::class);
    }

    
    /**
     * Les inscriptions réalisées dans cet établissement.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function cycles(): BelongsToMany
    {
        return $this->belongsToMany(Cycle::class, 'cycle_school')
                    ->withPivot('is_active')
                    ->withTimestamps();
    }

    public function series(): BelongsToMany
    {
        return $this->belongsToMany(Serie::class, 'school_series')
                    ->withPivot('is_active')
                    ->withTimestamps();
    }

    public function levels(): BelongsToMany
    {
        return $this->belongsToMany(Level::class, 'level_school')
                    ->withPivot('is_active')
                    ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES (Filtres réutilisables)
    |--------------------------------------------------------------------------
    */

    /**
     * Scope pour filtrer uniquement les établissements actifs.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSEURS & HELPER METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Retourne l'URL complète du logo ou une image par défaut.
     */
    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path 
            ? asset('storage/' . $this->logo_path) 
            : asset('images/default-school-logo.png');
    }

    /**
     * Retourne l'URL complète du cachet officiel.
     */
    public function getStampUrlAttribute(): ?string
    {
        return $this->stamp_path 
            ? asset('storage/' . $this->stamp_path) 
            : null;
    }

    /**
     * Adresse complète formatée sur une ligne.
     */
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->municipality,
            $this->city,
            $this->po_box,
        ]);

        return implode(', ', $parts);
    }
}