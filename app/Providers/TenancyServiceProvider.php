<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Stancl\Tenancy\Events\TenancyInitialized;
use Stancl\Tenancy\Events\TenantCreated;
use App\Listeners\SeedTenantRolesAndPermissions;
use App\Listeners\MigrateTenantSolution;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Config;
use App\Models\User;

class TenancyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // 1. Exécuter les migrations spécifiques du module
        Event::listen(
            TenantCreated::class,
            MigrateTenantSolution::class
        );

        // 2. Déclencher le seeding des rôles
        Event::listen(
            TenantCreated::class,
            SeedTenantRolesAndPermissions::class
        );

        // 3. Initialisation du contexte Tenant
        Event::listen(TenancyInitialized::class, function (TenancyInitialized $event) {
            // 1. Basculer le modèle d'authentification sur le modèle Tenant
            Config::set('auth.providers.users.model', User::class);
        
            // 2. Déconnecter temporairement les guards enregistrés en mémoire
            Auth::forgetGuards();
        
            // 3. Re-déclarer l'intercepteur de sécurité dans le contexte du Tenant
            Gate::before(function (User $user, string $ability) {
                // Si l'utilisateur est super-admin
                if ($user->hasRole('super-admin')) {
                    return true;
                }
        
                // Si l'utilisateur a la permission spécifique via Laratrust
                if ($user->isAbleTo($ability)) {
                    return true;
                }
        
                return null; // Laisser couler vers les autres règles si besoin
            });
        });
    }
}