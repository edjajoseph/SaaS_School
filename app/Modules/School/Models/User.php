<?php

namespace App\Modules\School\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements LaratrustUser
{
    use HasFactory, Notifiable, HasRolesAndPermissions, SoftDeletes;

    /**
     * Mappe le modèle Tenant sur la classe attendue par Laratrust
     */
    public function getMorphClass()
    {
        return \App\Models\User::class;
    }

    protected $table = 'users';

    protected $fillable = [
        'personne_id',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    /**
     * Gestion dynamique de la connexion (Multi-tenant vs Landlord/Central)
     */
    public function getConnectionName()
    {
        if (function_exists('tenant') && tenant('id')) {
            return 'tenant';
        }

        return $this->connection ?? config('database.default');
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Relation vers la personne associée.
     */
    public function personne(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'personne_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    public function scopeUnverified($query)
    {
        return $query->whereNull('email_verified_at');
    }

    public function getSchoolIdAttribute(): ?int
    {
        // On vérifie la présence de personne, de sa relation staff et de l'école associée
        return $this->personne?->staff?->school_id 
            ?? $this->personne?->staff?->school?->id;
    }
}