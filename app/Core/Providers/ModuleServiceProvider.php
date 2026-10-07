<?php

namespace App\Core\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Phase 1 : Enregistrement des ServiceProviders spécifiques à chaque module
     */
    public function register(): void
    {
        $this->loadModules(function (string $modulePath, string $moduleName) {
            $providerClass = "App\\Modules\\{$moduleName}\\Providers\\{$moduleName}ServiceProvider";
            
            if (class_exists($providerClass)) {
                $this->app->register($providerClass);
            }
        });
    }

    /**
     * Phase 2 : Démarrage des assets du module (Routes, Views, Migrations)
     */
    public function boot(): void
    {
        $this->loadModules(function (string $modulePath, string $moduleName) {
            $this->bootModule($modulePath, $moduleName);
        });
    }

    /**
     * Parcourt le dossier app/Modules
     */
    protected function loadModules(callable $callback): void
    {
        $modulesPath = app_path('Modules');

        if (!File::exists($modulesPath)) {
            return;
        }

        foreach (File::directories($modulesPath) as $modulePath) {
            $moduleName = basename($modulePath);
            $callback($modulePath, $moduleName);
        }
    }

    /**
     * Chargement effectif des éléments d'un module
     */
    protected function bootModule(string $modulePath, string $moduleName): void
    {
        $lowerModuleName = strtolower($moduleName);

        // 1. Chargement des Migrations
        /*if (File::exists($modulePath . '/Database/Migrations')) {
            $this->loadMigrationsFrom($modulePath . '/Database/Migrations');
        }*/

        // 2. Chargement des Vues (ex: view('hotel::room.index'))
        if (File::exists($modulePath . '/Views')) {
            $this->loadViewsFrom($modulePath . '/Views', $lowerModuleName);
        }

        // 3. Chargement des Routes Web (Unifié avec le middleware tenant)
        if (File::exists($modulePath . '/Routes/web.php')) {
            Route::middleware(['web', 'tenant']) // Inclut l'isolation tenant
                ->group($modulePath . '/Routes/web.php');
        }

        // 4. Chargement des Routes API (Unifié avec prefix et middleware tenant)
        if (File::exists($modulePath . '/Routes/api.php')) {
            Route::middleware(['api', 'tenant'])
                ->prefix("api/{$lowerModuleName}")
                ->group($modulePath . '/Routes/api.php');
        }
    }
}