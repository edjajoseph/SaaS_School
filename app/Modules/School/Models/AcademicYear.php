<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class AcademicYear extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 
        'start_date', 
        'end_date', 
        'is_current'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_current' => 'boolean',
    ];

    /**
     * Relation avec les périodes académiques planifiées (anciennement terms)
     */
    public function academicPeriods(): HasMany
    {
        return $this->hasMany(AcademicPeriod::class);
    }

    /**
     * Récupérer la période académique active en cours pour cette année
     */
    public function currentAcademicPeriod(): HasOne
    {
        return $this->hasOne(AcademicPeriod::class)->where('is_current', true);
    }

    /**
     * Relation avec les inscriptions
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Alias rétrocompatible (si vous souhaitez conserver la méthode $academicYear->terms)
     */
    public function terms(): HasMany
    {
        return $this->academicPeriods();
    }

    /**
     * Alias rétrocompatible pour la période courante
     */
    public function currentTerm(): HasOne
    {
        return $this->currentAcademicPeriod();
    }

    public function setAsCurrentForSchool(School $school): void
    {
        DB::transaction(function () use ($school) {
            // 1. Réinitialiser is_current sur toutes les années de cette école
            // (Si les années sont liées aux écoles ou via pivot)
            static::query()->update(['is_current' => false]);
            $this->update(['is_current' => true]);

            // 2. Mettre à jour l'année courante au niveau de l'école
            $school->update([
                'current_academic_year_id' => $this->id,
            ]);

            // 3. Basculer TOUS les étudiants de cette école en 'inactive' pour la nouvelle rentrée
            Student::where('school_id', $school->id)->update([
                'status' => 'inactive',
            ]);
        });
    }
}