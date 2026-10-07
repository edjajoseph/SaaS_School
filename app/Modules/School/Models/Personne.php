<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Personne extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nom',
        'prenoms',
        'sexe',
        'civility',
        'sit_mat',
        'birth_date',
        'birth_place',
        'country_id',
        'telephone',
        'email',
        'address',
        'photo',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    /**
     * Nettoyage automatique du NOM : Majuscules, sans accents, conserve les tirets et espaces
     */
    protected function nom(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => $value ? Str::of($value)
                ->replaceMatches('/[^\p{L}\s\-]/u', '') // Conserve toutes les lettres Unicode, espaces et tirets
                ->ascii()                               // Convertit les accents (ex: É -> E)
                ->squish()                              // Nettoie les espaces multiples
                ->upper()                               // Passe en majuscules
                ->toString() : null,
        );
    }

    /**
     * Nettoyage automatique des PRÉNOMS : Majuscules, sans accents, conserve les tirets et espaces
     */
    protected function prenoms(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => $value ? Str::of($value)
                ->replaceMatches('/[^\p{L}\s\-]/u', '') // Conserve toutes les lettres Unicode, espaces et tirets
                ->ascii()                               // Convertit les accents (ex: É -> E)
                ->squish()                              // Nettoie les espaces multiples
                ->upper()                               // Passe en majuscules
                ->toString() : null,
        );
    }

    /**
     * Accessor pour le Nom Complet
     */
    public function getNomCompletAttribute(): string
    {
        return trim("{$this->nom} {$this->prenoms}");
    }

    // --- RELATIONS ---

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function student(): HasOne
    {
        return $this->hasOne(Student::class, 'personne_id');
    }

    public function staff(): HasOne
    {
        return $this->hasOne(Staff::class, 'personne_id');
    }
}