<?php

namespace App\Modules\School\Providers;

use Illuminate\Support\ServiceProvider;
use App\Modules\School\Models\Registration;
use App\Modules\School\Observers\RegistrationObserver;

class SchoolServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // ...
    }

    public function boot(): void
    {
        // DESACTIVER cette ligne pour éviter la création des tables dans la BDD centrale
        // $this->loadMigrationsFrom(app_path('Modules/School/Database/Migrations'));
        
        // Conserver uniquement les vues et routes
        $this->loadViewsFrom(app_path('Modules/School/Views'), 'School');
        $this->loadRoutesFrom(app_path('Modules/School/Routes/web.php'));

        // Enregistrement de l'observer pour la gestion automatique du statut des étudiants
        Registration::observe(RegistrationObserver::class);
    }
}