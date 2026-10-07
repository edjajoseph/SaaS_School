<?php

namespace App\Listeners;

use Stancl\Tenancy\Events\TenantCreated;
use Illuminate\Support\Facades\Artisan;
// 1. Importer le BON Seeder situé dans le sous-dossier Tenant
use Database\Seeders\Tenant\LaratrustSeeder;

class SeedTenantRolesAndPermissions
{
    public function handle(TenantCreated $event): void
    {
        // 2. Exécution ciblée du seeder tenant de Laratrust
        Artisan::call('tenants:seed', [
            '--tenants' => [$event->tenant->id],
            '--class'   => LaratrustSeeder::class,
            '--force'   => true,
        ]);
    }
}