<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CentralDatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            CentralAdminSeeder::class,
            SolutionSeeder::class,
            PermissionTableSeeder::class,
            PlansTableSeeder::class,
        ]);
    }
}