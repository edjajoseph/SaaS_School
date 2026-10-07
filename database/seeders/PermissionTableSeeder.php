<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PermissionTableSeeder extends Seeder
{
    /**
     * Tables techniques / système à ignorer.
     */
    protected array $excludedTables = [
        // Migrations et système Laravel
        'migrations',
        'failed_jobs',
        'jobs',
        'job_batches',
        'cache',
        'cache_locks',
        'sessions',
        'password_resets',
        'password_reset_tokens',
        'personal_access_tokens',
        'central_sessions',

        // Tables pivots (Laratrust / Multi-tenant)
        'permission_role',
        'permission_user',
        'role_user',
        'role_permission',
        'tenant_routes',
    ];

    /**
     * Actions standards CRUD à générer.
     */
    protected array $actions = [
        'access' => 'Accès global au module',
        'show'   => 'Consulter / Lister',
        'create' => 'Créer / Ajouter',
        'update' => 'Modifier / Éditer',
        'delete' => 'Supprimer',
    ];

    public function run(): void
    {
        // 1. Récupération de toutes les tables de la base de données
        $tables = DB::select('SHOW TABLES');
        $dbName = DB::getDatabaseName();
        $keyName = "Tables_in_{$dbName}";

        foreach ($tables as $table) {
            $tableName = $table->$keyName;

            // 2. On vérifie si la table fait partie de la liste d'exclusion
            if (in_array(strtolower($tableName), array_map('strtolower', $this->excludedTables))) {
                continue;
            }

            // 3. Formatage du Module et de la Fonctionnalité
            $moduleName  = Str::title(str_replace('_', ' ', $tableName));
            $featureName = $moduleName;

            // 4. Génération des permissions
            foreach ($this->actions as $action => $actionLabel) {
                
                $technicalName = Str::slug($moduleName, '_') . '.' . Str::slug($featureName, '_') . '.' . $action;

                Permission::firstOrCreate(
                    ['name' => $technicalName],
                    [
                        'module'       => $moduleName,
                        'feature'      => $featureName,
                        'display_name' => "{$featureName} - {$actionLabel}",
                        'description'  => "Permission pour {$actionLabel} sur la table {$tableName}",
                    ]
                );
            }
        }

        $this->command->info('Permissions métier générées avec succès !');
    }
}