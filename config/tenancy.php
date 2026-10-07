<?php

declare(strict_types=1);

use Stancl\Tenancy\Database\Models\Domain;
use Stancl\Tenancy\Database\Models\Tenant;

return [
    'tenant_model' => \App\Models\Tenant::class,
    'id_generator' => Stancl\Tenancy\UUIDGenerator::class,

    'domain_model' => Domain::class,

    /**
     * The list of domains hosting your central app.
     */
    'central_domains' => [
        parse_url(env('APP_URL'), PHP_URL_HOST) ?? 'saas-hotel.test',
        'localhost',
    ],

    /**
     * Tenancy bootstrappers.
     */
    'bootstrappers' => [
        Stancl\Tenancy\Bootstrappers\DatabaseTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\FilesystemTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\QueueTenancyBootstrapper::class,
    ],

    /**
     * Database tenancy config.
     */
    'database' => [
        'central_connection' => env('DB_CONNECTION', 'central'),
        'template_tenant_connection' => null,
        'prefix' => 'tenant',
        'suffix' => '',

        'managers' => [
            'sqlite' => Stancl\Tenancy\TenantDatabaseManagers\SQLiteDatabaseManager::class,
            'mysql' => Stancl\Tenancy\TenantDatabaseManagers\MySQLDatabaseManager::class,
            'mariadb' => Stancl\Tenancy\TenantDatabaseManagers\MySQLDatabaseManager::class,
            'pgsql' => Stancl\Tenancy\TenantDatabaseManagers\PostgreSQLDatabaseManager::class,
        ],
    ],

    /**
     * Cache tenancy config.
     */
    'cache' => [
        'tag_base' => 'tenant',
    ],

    /**
     * Filesystem tenancy config.
     */
    'filesystem' => [
        'suffix_base' => 'tenant',
        'disks' => [
            'local',
            'public',
        ],

        'root_override' => [
            'local' => '%storage_path%/app/',
            'public' => '%storage_path%/app/public/',
        ],

        'suffix_storage_path' => true,
        'asset_helper_tenancy' => true,
    ],

    /**
     * Redis tenancy config.
     */
    'redis' => [
        'prefix_base' => 'tenant',
        'prefixed_connections' => [],
    ],

    /**
     * Features standard de tenancy.
     */
    'features' => [],

    'routes' => true,

    /**
     * Événements déclenchés lors de la création d'un tenant.
     */
    'events' => [
        Stancl\Tenancy\Events\TenantCreated::class => [
            Stancl\Tenancy\Jobs\CreateDatabase::class,
            Stancl\Tenancy\Jobs\MigrateDatabase::class,
            Stancl\Tenancy\Jobs\SeedDatabase::class, // Réactivé pour exécuter le seeding
        ],
    ],

    /**
     * Paramètres de migration : inclure tous les répertoires contenant vos tables tenant.
     */
    // config/tenancy.php 
    'migration_parameters' => [
        '--force' => true,
        '--path' => [
            database_path('migrations/tenant'),
            base_path('app/Modules/School/Database/Migrations'),
        ],
        '--realpath' => true,
    ],

    /**
     * Paramètres de seeding.
     */
    'seeder_parameters' => [
        '--class' => \Database\Seeders\TenantDatabaseSeeder::class,
        '--force' => true,
    ],
];