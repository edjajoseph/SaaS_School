<?php

namespace App\Listeners;

use Stancl\Tenancy\Events\TenantCreated;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class MigrateTenantSolution
{
    public function handle(TenantCreated $event): void
    {
        $tenant = $event->tenant;

        // 1. Récupérer le code de la solution associée
        $solutionCode = null;

        if (!empty($tenant->solution_id)) {
            $centralDb = config('database.connections.mysql.database');
            $solution = DB::table("{$centralDb}.solutions")->where('id', $tenant->solution_id)->first();

            if ($solution) {
                // ex: 'school', 'hotel', 'church', 'rh'
                $solutionCode = $solution->code ?? $solution->slug ?? $solution->type;
            }
        }

        if (!$solutionCode) {
            return;
        }

        // Nom du dossier avec la première lettre en majuscule (ex: School, Hotel, RH)
        $moduleName = ucfirst(strtolower($solutionCode));
        $relativeMigrationPath = "app/Modules/{$moduleName}/Database/Migrations";
        $fullPath = base_path($relativeMigrationPath);

        // 2. Vérifier si le dossier de migrations du module existe
        if (file_exists($fullPath)) {
            // Exécuter la migration spécifique au sein du contexte du tenant
            $tenant->run(function () use ($relativeMigrationPath) {
                Artisan::call('migrate', [
                    '--path'     => $relativeMigrationPath,
                    '--force'    => true,
                ]);
            });
        }
    }
}