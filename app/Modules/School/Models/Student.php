<?php

namespace App\Modules\School\Models;

//use App\Models\Country; // Ajuste le namespace de Country selon ton projet
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'personne_id',
        'matricule',
        'nationality',
        'father_name',
        'father_job',
        'father_phone',
        'mother_name',
        'mother_job',
        'mother_phone',
        'guardian_name',
        'guardian_relation',
        'guardian_phone',
    ];

    /**
     * Relation vers la fiche Personne (Héritage)
     */
    public function personne(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'personne_id');
    }

    /**
     * Relation vers le pays de nationalité
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'nationality');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(StudentDocument::class);
    }
}