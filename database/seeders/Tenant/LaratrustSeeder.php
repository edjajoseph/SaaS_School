<?php

namespace Database\Seeders\Tenant;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class LaratrustSeeder extends Seeder
{
    public function run()
    {
        // 1. Désactivation temporaire des caches pour le seeding
        config(['cache.default' => 'file']);
        config(['laratrust.cache.enabled' => false]);
        config(['laratrust.panel.cache.enabled' => false]);

        $this->truncateLaratrustTables();

        // 2. Définition des modules et de leurs actions CRUD associées
        $modules = [
            // Général & Référentiels Académiques
            'dashboard'            => ['display' => 'Tableau de bord', 'actions' => ['read']],
            'general'              => ['display' => 'Section Générale', 'actions' => ['read']],
            'settings-academic'   => ['display' => 'Paramètres Académiques', 'actions' => ['read', 'update']],
            'degrees'              => ['display' => 'Diplômes', 'actions' => ['create', 'read', 'update', 'delete']],
            'period-types'         => ['display' => 'Types de Découpage', 'actions' => ['create', 'read', 'update', 'delete']],
            'cycles'               => ['display' => 'Cycles d\'études', 'actions' => ['create', 'read', 'update', 'delete']],
            'levels'               => ['display' => 'Niveaux d\'études', 'actions' => ['create', 'read', 'update', 'delete']],
            'series'               => ['display' => 'Séries et Filières', 'actions' => ['create', 'read', 'update', 'delete']],
            'subjects'             => ['display' => 'Matières Générales', 'actions' => ['create', 'read', 'update', 'delete']],

            // Référentiels RH & Localisation
            'settings-rh'          => ['display' => 'Paramètres RH', 'actions' => ['read', 'update']],
            'staff-roles'          => ['display' => 'Fonctions RH', 'actions' => ['create', 'read', 'update', 'delete']],
            'specialities'         => ['display' => 'Spécialités', 'actions' => ['create', 'read', 'update', 'delete']],
            'document-types'       => ['display' => 'Types de Documents', 'actions' => ['create', 'read', 'update', 'delete']],
            'settings-local'       => ['display' => 'Paramètres de Localisation', 'actions' => ['read', 'update']],
            'countries'            => ['display' => 'Pays', 'actions' => ['create', 'read', 'update', 'delete']],

            // Organisation & Offres Académiques
            'organisation'         => ['display' => 'Bloc Organisation', 'actions' => ['read']],
            'schools'              => ['display' => 'Établissements', 'actions' => ['create', 'read', 'update', 'delete']],
            'academic-years'       => ['display' => 'Années Scolaires', 'actions' => ['create', 'read', 'update', 'delete']],
            'periods'              => ['display' => 'Périodes Académiques', 'actions' => ['create', 'read', 'update', 'delete']],
            'classes'              => ['display' => 'Classes', 'actions' => ['create', 'read', 'update', 'delete']],
            'academic-offers'      => ['display' => 'Offres Académiques', 'actions' => ['read']],
            'school-series'        => ['display' => 'Filières d\'Établissement', 'actions' => ['create', 'read', 'update', 'delete']],
            'teaching-units'       => ['display' => 'Unités d\'Enseignement (UE)', 'actions' => ['create', 'read', 'update', 'delete']],
            'school-subjects'      => ['display' => 'ECUE / Matières', 'actions' => ['create', 'read', 'update', 'delete']],
            'evaluation-types'     => ['display' => 'Types d\'Évaluation', 'actions' => ['create', 'read', 'update', 'delete']],

            // Planning, Personnel & Ressources
            'staff'                => ['display' => 'Personnel PAT/Enseignants', 'actions' => ['create', 'read', 'update', 'delete']],
            'rooms'                => ['display' => 'Salles de cours', 'actions' => ['create', 'read', 'update', 'delete']],
            'schedules'            => ['display' => 'Emplois du temps', 'actions' => ['create', 'read', 'update', 'delete']],
            'leave-requests'       => ['display' => 'Demande d\'autorisation d\'absence', 'actions' => ['create', 'read', 'update', 'delete']],

            // Inscriptions & Étudiants
            'students'             => ['display' => 'Étudiants', 'actions' => ['create', 'read', 'update', 'delete']],
            'registrations'        => ['display' => 'Inscriptions', 'actions' => ['create', 'read', 'update', 'delete']],

            // Évaluations & Bulletins
            'evaluations'          => ['display' => 'Évaluations et Notes', 'actions' => ['create', 'read', 'update', 'delete']],
            'reports'              => ['display' => 'Bulletins et PV', 'actions' => ['create', 'read', 'update', 'delete']],

            // Suivi des Présences & Bornes
            'attendance-student'   => ['display' => 'Pointage Étudiants', 'actions' => ['create', 'read', 'update', 'delete']],
            'attendance-teacher'   => ['display' => 'Pointage Enseignants', 'actions' => ['create', 'read', 'update', 'delete']],
            'attendance-staff'     => ['display' => 'Pointage Personnel', 'actions' => ['create', 'read', 'update', 'delete']],
            'payroll'              => ['display' => 'Paie du Personnel', 'actions' => ['create', 'read', 'update', 'delete']],
            'terminals'            => ['display' => 'Bornes de Pointage', 'actions' => ['create', 'read', 'update', 'delete']],

            // Cahier de Textes
            'logbook'              => ['display' => 'Cahier de Textes', 'actions' => ['create', 'read', 'update', 'delete']],

            // Recouvrement & Finances (Exclus pour admin-ecole)
            'payments'             => ['display' => 'Paiements et Encaissements', 'actions' => ['create', 'read', 'update', 'delete']],
            'fee-plans'            => ['display' => 'Plans Tarifaires et Échéanciers', 'actions' => ['create', 'read', 'update', 'delete']],
            'financial-reports'    => ['display' => 'Rapports Financiers', 'actions' => ['read']],

            // Comptabilité Générale (Exclus pour admin-ecole)
            'accounting-entries'   => ['display' => 'Écritures Comptables', 'actions' => ['create', 'read', 'update', 'delete']],
            'accounting-statements'=> ['display' => 'Pièces et États Comptables', 'actions' => ['read']],

            // Sécurité et Administration
            'users'                => ['display' => 'Utilisateurs', 'actions' => ['create', 'read', 'update', 'delete']],
            'roles'                => ['display' => 'Rôles', 'actions' => ['create', 'read', 'update', 'delete']],
            'permissions'          => ['display' => 'Permissions', 'actions' => ['create', 'read', 'update', 'delete']],
        ];

        $actionLabels = [
            'create' => 'Créer / Ajouter',
            'read'   => 'Consulter / Afficher',
            'update' => 'Modifier / Éditer',
            'delete' => 'Supprimer',
        ];

        // 3. Génération et enregistrement des permissions
        $createdPermissions = [];
        foreach ($modules as $moduleKey => $config) {
            foreach ($config['actions'] as $action) {
                $permName = "{$action}-{$moduleKey}";
                $actionText = $actionLabels[$action] ?? ucfirst($action);
                
                $createdPermissions[$permName] = Permission::firstOrCreate(
                    ['name' => $permName],
                    [
                        'display_name' => "{$actionText} - {$config['display']}",
                        'description'  => "Droit de {$actionText} sur {$config['display']}"
                    ]
                );
            }
        }

        // 4. Définition des Rôles et de leurs périmètres
        $rolesStructure = [
            'super-admin' => [
                'display_name' => 'Super Administrateur',
                'description'  => 'Accès total absolu à l\'ensemble du système',
                'permissions'  => [] // Reçoit dynamiquement toutes les permissions
            ],
            'admin-ecole' => [
                'display_name' => 'Administrateur d\'Établissement',
                'description'  => 'Gestion globale de l\'établissement hors finances/comptabilité et hors super-admin',
                'permissions'  => array_filter(array_keys($createdPermissions), function ($permName) {
                    // Exclure totalement les modules de Recouvrement/Finances et Comptabilité
                    $financialModules = [
                        'payments', 
                        'fee-plans', 
                        'financial-reports', 
                        'accounting-entries', 
                        'accounting-statements'
                    ];

                    foreach ($financialModules as $mod) {
                        if (str_ends_with($permName, "-{$mod}")) {
                            return false;
                        }
                    }

                    // Exclure la possibilité d'éditer ou supprimer les permissions système super-admin
                    if (in_array($permName, ['create-permissions', 'update-permissions', 'delete-permissions'])) {
                        return false;
                    }

                    return true;
                })
            ],
            'directeur-etudes' => [
                'display_name' => 'Directeur des Études',
                'description'  => 'Gestion académique, planification, enseignements et évaluations',
                'permissions'  => [
                    'read-dashboard', 'read-general',
                    'read-settings-academic', 'update-settings-academic',
                    'create-degrees', 'read-degrees', 'update-degrees',
                    'create-period-types', 'read-period-types', 'update-period-types',
                    'create-cycles', 'read-cycles', 'update-cycles',
                    'create-levels', 'read-levels', 'update-levels',
                    'create-series', 'read-series', 'update-series',
                    'create-subjects', 'read-subjects', 'update-subjects',
                    'read-organisation', 'create-schools', 'read-schools', 'update-schools',
                    'create-academic-years', 'read-academic-years', 'update-academic-years',
                    'create-periods', 'read-periods', 'update-periods',
                    'create-classes', 'read-classes', 'update-classes',
                    'read-academic-offers', 'create-school-series', 'read-school-series', 'update-school-series',
                    'create-teaching-units', 'read-teaching-units', 'update-teaching-units',
                    'create-school-subjects', 'read-school-subjects', 'update-school-subjects',
                    'create-evaluation-types', 'read-evaluation-types', 'update-evaluation-types',
                    'read-staff', 'create-rooms', 'read-rooms', 'update-rooms',
                    'create-schedules', 'read-schedules', 'update-schedules',
                    'read-students', 'create-registrations', 'read-registrations', 'update-registrations',
                    'create-evaluations', 'read-evaluations', 'update-evaluations',
                    'create-reports', 'read-reports', 'update-reports',
                    'read-attendance-student', 'read-attendance-teacher',
                    'create-logbook', 'read-logbook', 'update-logbook',
                    'create-leave-requests','read-leave-requests','update-leave-requests',
                ]
            ],
            'comptable' => [
                'display_name' => 'Comptable / Agent de Recouvrement',
                'description'  => 'Gestion financière, encaissements et comptabilité générale',
                'permissions'  => [
                    'read-dashboard', 'read-general',
                    'read-students', 'read-registrations',
                    'read-payroll', 'create-payroll', 'update-payroll',
                    'create-payments', 'read-payments', 'update-payments',
                    'create-fee-plans', 'read-fee-plans', 'update-fee-plans',
                    'read-financial-reports',
                    'create-accounting-entries', 'read-accounting-entries', 'update-accounting-entries',
                    'read-accounting-statements',
                    'create-leave-requests','read-leave-requests'
                ]
            ],
            'secretaire' => [
                'display_name' => 'Secrétaire / Agent de Scolarité',
                'description'  => 'Gestion des inscriptions et suivi administratif quotidien',
                'permissions'  => [
                    'read-dashboard', 'read-general',
                    'read-staff', 'read-rooms', 'read-schedules',
                    'create-students', 'read-students', 'update-students',
                    'create-registrations', 'read-registrations', 'update-registrations',
                    'create-attendance-student', 'read-attendance-student',
                    'create-attendance-staff', 'read-attendance-staff',
                    'create-payments', 'read-payments',
                    'create-leave-requests','read-leave-requests'
                ]
            ],
            'enseignant' => [
                'display_name' => 'Enseignant',
                'description'  => 'Saisie des cours, notes et gestion des présences',
                'permissions'  => [
                    'read-dashboard',
                    'create-evaluations', 'read-evaluations', 'update-evaluations',
                    'create-attendance-student', 'read-attendance-student',
                    'read-attendance-teacher',
                    'create-logbook', 'read-logbook', 'update-logbook',
                    'create-leave-requests','read-leave-requests'
                ]
            ],
            'etudiant' => [
                'display_name' => 'Étudiant',
                'description'  => 'Consultation des cours, emplois du temps et résultats',
                'permissions'  => [
                    'read-dashboard',
                    'read-schedules',
                    'read-reports',
                    'read-attendance-student',
                    'read-logbook',
                ]
            ],
        ];

        $allPermissionIds = array_values(array_map(fn($p) => $p->id, $createdPermissions));

        // 5. Création des rôles et synchronisation des permissions
        foreach ($rolesStructure as $roleKey => $details) {
            $role = Role::firstOrCreate(
                ['name' => $roleKey],
                [
                    'display_name' => $details['display_name'],
                    'description'  => $details['description']
                ]
            );

            if ($roleKey === 'super-admin') {
                // Attribution exclusive de TOUTES les permissions au super-admin
                $role->permissions()->sync($allPermissionIds);
            } else {
                $permIds = collect($details['permissions'])
                    ->map(fn($pName) => $createdPermissions[$pName]->id ?? null)
                    ->filter()
                    ->toArray();

                $role->permissions()->sync($permIds);
            }
        }

        // 6. Attacher le rôle super-admin à l'utilisateur initial
        if (Config::get('laratrust_seeder.create_users', true)) {
            $superAdminRole = Role::where('name', 'super-admin')->first();

            if ($superAdminRole) {
                $tenant = tenant();
                $domain = $tenant ? ($tenant->domains()->first()->domain ?? ($tenant->id . '.' . config('tenancy.central_domains.0'))) : 'localhost';
                $email = 'admin@' . $domain;

                $adminUser = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name'     => 'Administrateur',
                        'password' => bcrypt('password')
                    ]
                );

                if (!$adminUser->hasRole('super-admin')) {
                    $adminUser->syncRoles([$superAdminRole->id]);
                }
            }
        }
    }

    public function truncateLaratrustTables()
    {
        Schema::disableForeignKeyConstraints();

        DB::table('permission_role')->truncate();
        DB::table('permission_user')->truncate();
        DB::table('role_user')->truncate();

        if (Config::get('laratrust_seeder.truncate_tables', true)) {
            DB::table('roles')->truncate();
            DB::table('permissions')->truncate();

            if (Config::get('laratrust_seeder.create_users', true)) {
                $usersTable = (new User)->getTable();
                DB::table($usersTable)->truncate();
            }
        }

        Schema::enableForeignKeyConstraints();
    }
}