<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\Tenant\LaratrustSeeder;
use App\Modules\School\Database\Seeders\SerieSeeder;
use App\Modules\School\Database\Seeders\CycleSeeder;
use App\Modules\School\Database\Seeders\LevelSeeder;
use App\Modules\School\Database\Seeders\PeriodTypeSeeder;
use App\Modules\School\Database\Seeders\CountrySeeder;
use App\Modules\School\Database\Seeders\DegreeSeeder;
use App\Modules\School\Database\Seeders\SpecialitySeeder;
use App\Modules\School\Database\Seeders\StaffRoleSeeder;
use App\Modules\School\Database\Seeders\SubjectSeeder;
use App\Modules\School\Database\Seeders\AccountingJournalSeeder;
use App\Modules\School\Database\Seeders\ChartOfAccountsSeeder;

class TenantDatabaseSeeder extends Seeder
{
    /**
     * Run the tenant database seeds.
     */
    public function run(): void
    {
        $this->call([
            // 1. Rôles et Permissions (Laratrust)
            LaratrustSeeder::class,

            // 2. Données de référence du module School
            SerieSeeder::class,
            CycleSeeder::class,
            LevelSeeder::class,
            PeriodTypeSeeder::class,
            CountrySeeder::class,
            DegreeSeeder::class,
            SpecialitySeeder::class,
            StaffRoleSeeder::class,
            SubjectSeeder::class,
            AccountingJournalSeeder::class,
            ChartOfAccountsSeeder::class,
            DocumentTypeSeeder::class,
        ]);
    }
}