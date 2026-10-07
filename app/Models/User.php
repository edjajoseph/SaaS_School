<?php

namespace App\Models;

use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Stancl\Tenancy\Database\Concerns\CentralConnection; // Si besoin pour exclure
use Stancl\Tenancy\Database\Concerns\TenantConnection;  // Permet de forcer la connexion tenant quand initialisé

class User extends Authenticatable implements LaratrustUser
{
    use HasRolesAndPermissions, HasFactory, Notifiable;
    
    
    /**
     * Définit dynamiquement la connexion 'tenant' lorsque Tenancy est initialisé
     */
    
    protected $fillable = [
        'personne_id',
        'name',
        'email',
        'password',
        'pwd_change',
        'isactive',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'pwd_change' => 'boolean',
        'isactive' => 'boolean',
    ];

    public function personne()
    {
        return $this->belongsTo(Personne::class);
    }

    public function canPerform(string $permission): bool
    {
        if ($this->hasRole('super-admin')) {
            return true;
        }

        return $this->isAbleTo($permission);
    }

    public function getConnectionName()
    {
        if (function_exists('tenant') && tenant('id')) {
            return 'tenant';
        }

        return $this->connection ?? config('database.default');
    }
}