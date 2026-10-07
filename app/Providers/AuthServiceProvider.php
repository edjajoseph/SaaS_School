<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPolicies();
        
        // Intercepteur global de sécurité
        Gate::before(function (User $user, string $ability) {
            // 1. Bypass automatique pour le Super Admin (Autorise tout)
            if ($user->hasRole('super-admin')) {
                return true;
            }

            // 2. Si l'utilisateur a la permission via Laratrust
            if ($user->isAbleTo($ability)) {
                return true;
            }

            // IMPORTANT : Ne pas retourner false ici ! 
            // Retourner null permet à Laravel de poursuivre l'évaluation normale.
            return null;
        });
    }
}