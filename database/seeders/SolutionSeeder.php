<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SolutionSeeder extends Seeder
{
    public function run(): void
    {
        $apps = [
            [
                'code' => 'hotel',
                'nom'  => 'Gestion Hôtelière & Hébergement',
                'description' => 'Module complet de gestion des chambres, réservations et facturation hôtelière.',
            ],
            [
                'code' => 'school',
                'nom'  => 'Gestion Scolaire & Éducation',
                'description' => 'Gestion des élèves, notes, inscriptions et scolarités.',
            ],
            [
                'code' => 'hr',
                'nom'  => 'Ressources Humaines (RH)',
                'description' => 'Gestion de la paie, congés, présences et employés.',
            ],
            [
                'code' => 'church',
                'nom'  => 'Gestion des églises',
                'description' => 'Gestion des cultes.',
            ],
        ];

        foreach ($apps as $app) {
            \App\Models\Solution::firstOrCreate(['code' => $app['code']], $app);
        }
    }
}