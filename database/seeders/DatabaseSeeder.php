<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Si la constante ou le helper tenancy existe et qu'un tenant est actif
        if (function_exists('tenant') && tenant()) {
            $this->call(TenantDatabaseSeeder::class);
        } else {
            $this->call(CentralDatabaseSeeder::class);
        }
    }
}