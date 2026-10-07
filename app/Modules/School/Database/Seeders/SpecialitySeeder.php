<?php

namespace App\Modules\School\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialitySeeder extends Seeder
{
    public function run(): void
    {
        $specialities = [
            // --- Enseignement général & Sciences ---
            ['id' => 1, 'code' => 'MATH', 'name' => 'Mathématiques', 'description' => 'Enseignement et recherche en mathématiques pures et appliquées', 'is_active' => true],
            ['id' => 2, 'code' => 'PHYS_CHEM', 'name' => 'Physique - Chimie', 'description' => 'Sciences physiques et chimie fondamentale/appliquée', 'is_active' => true],
            ['id' => 3, 'code' => 'SVT', 'name' => 'Sciences de la Vie et de la Terre', 'description' => 'Biologie, géologie et sciences de l\'environnement', 'is_active' => true],
            ['id' => 4, 'code' => 'FRENCH', 'name' => 'Lettres Modernes / Français', 'description' => 'Langue française, littérature et expression', 'is_active' => true],
            ['id' => 5, 'code' => 'ENGLISH', 'name' => 'Anglais', 'description' => 'Langue, littérature et civilisation anglosaxonne', 'is_active' => true],
            ['id' => 6, 'code' => 'HIST_GEO', 'name' => 'Histoire - Géographie', 'description' => 'Histoire, géographie et géopolitique', 'is_active' => true],
            ['id' => 7, 'code' => 'PHILOSOPHY', 'name' => 'Philosophie', 'description' => 'Philosophie générale et pensée critique', 'is_active' => true],
            ['id' => 8, 'code' => 'EPS', 'name' => 'Éducation Physique et Sportive', 'description' => 'Sciences et techniques des activités physiques et sportives', 'is_active' => true],

            // --- Informatique & Technologies ---
            ['id' => 9, 'code' => 'DEV_FULLSTACK', 'name' => 'Développement Full-Stack', 'description' => 'Conception et développement d\'applications web et mobiles', 'is_active' => true],
            ['id' => 10, 'code' => 'NET_ADMIN', 'name' => 'Administration Réseaux & Systèmes', 'description' => 'Gestion des infrastructures réseau, routage et commutation', 'is_active' => true],
            ['id' => 11, 'code' => 'CYBERSEC', 'name' => 'Cybersécurité & Sécurité Systèmes', 'description' => 'Sécurisation des interconnexions, audits et architecture de sécurité', 'is_active' => true],
            ['id' => 12, 'code' => 'DATA_BI', 'name' => 'Data Science & Business Intelligence', 'description' => 'Analyse de données, modélisation et reporting', 'is_active' => true],
            ['id' => 13, 'code' => 'CLOUD_DEVOPS', 'name' => 'Cloud Computing & DevOps', 'description' => 'Déploiement, automatisation et gestion des environnements virtuels', 'is_active' => true],
            ['id' => 14, 'code' => 'AGRI_TECH', 'name' => 'AgriTech & Supply Chain', 'description' => 'Digitalisation et optimisation des chaînes d\'approvisionnement', 'is_active' => true],
        ];

        $now = now();
        foreach ($specialities as &$item) {
            $item['created_at'] = $now;
            $item['updated_at'] = $now;
        }

        DB::table('specialities')->upsert(
            $specialities,
            ['id'],
            ['code', 'name', 'description', 'is_active', 'updated_at']
        );
    }
}