<?php

namespace App\Modules\School\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaffRoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['id' => 1, 'code' => 'ADMIN', 'name' => 'Administrateur', 'description' => 'Gestion complète du système et des paramètres', 'is_active' => true],
            ['id' => 2, 'code' => 'INSTRUCTOR', 'name' => 'Enseignant / Formateur', 'description' => 'Gestion des cours, des évaluations et du suivi académique', 'is_active' => true],
            ['id' => 3, 'code' => 'PEDAGO_MGR', 'name' => 'Responsable Pédagogique', 'description' => 'Supervision des programmes, des classes et du corps professoral', 'is_active' => true],
            ['id' => 4, 'code' => 'SECRETARY', 'name' => 'Secrétaire / Agent d\'accueil', 'description' => 'Gestion des inscriptions et de l\'accueil des usagers', 'is_active' => true],
            ['id' => 5, 'code' => 'ACCOUNTANT', 'name' => 'Comptable / Gestionnaire financier', 'description' => 'Suivi des paiements, facturation et scolarités', 'is_active' => true],
            ['id' => 6, 'code' => 'IT_SUPPORT', 'name' => 'Support Informatique', 'description' => 'Gestion du parc, des accès et de l\'infrastructure', 'is_active' => true],
        ];

        $now = now();
        foreach ($roles as &$item) {
            $item['created_at'] = $now;
            $item['updated_at'] = $now;
        }

        DB::table('staff_roles')->upsert(
            $roles,
            ['id'],
            ['code', 'name', 'description', 'is_active', 'updated_at']
        );
    }
}