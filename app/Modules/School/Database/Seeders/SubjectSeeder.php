<?php

namespace App\Modules\School\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            // --- Enseignement Général & Sciences Fondamentales ---
            ['id' => 1, 'code' => 'MATH_GEN', 'name' => 'Mathématiques Générales', 'description' => 'Algèbre, analyse et calcul numérique', 'is_active' => true],
            ['id' => 2, 'code' => 'MATH_APP', 'name' => 'Mathématiques Appliquées', 'description' => 'Probabilités, statistiques et recherche opérationnelle', 'is_active' => true],
            ['id' => 3, 'code' => 'PHYS_GEN', 'name' => 'Physique Générale', 'description' => 'Mécanique, thermodynamique et électromagnétisme', 'is_active' => true],
            ['id' => 4, 'code' => 'CHEM_GEN', 'name' => 'Chimie Générale', 'description' => 'Chimie organique et inorganique', 'is_active' => true],
            ['id' => 5, 'code' => 'SVT_GEN', 'name' => 'Sciences de la Vie et de la Terre', 'description' => 'Biologie, géologie et sciences de l\'environnement', 'is_active' => true],
            ['id' => 6, 'code' => 'FREN_GEN', 'name' => 'Français / Expression écrite', 'description' => 'Techniques d\'expression, rédaction et grammaire', 'is_active' => true],
            ['id' => 7, 'code' => 'ANGL_GEN', 'name' => 'Anglais Général', 'description' => 'Langue anglaise et communication orale', 'is_active' => true],
            ['id' => 8, 'code' => 'ANGL_TECH', 'name' => 'Anglais Technique', 'description' => 'Anglais appliqué à l\'informatique et aux affaires', 'is_active' => true],
            ['id' => 9, 'code' => 'HIST_GEO', 'name' => 'Histoire - Géographie', 'description' => 'Histoire contemporaine et géopolitique', 'is_active' => true],
            ['id' => 10, 'code' => 'PHILOSOPHY', 'name' => 'Philosophie', 'description' => 'Introduction à la philosophie et éthique', 'is_active' => true],

            // --- Informatique & Génie Logiciel ---
            ['id' => 11, 'code' => 'ALGO_PROG', 'name' => 'Algorithmique & Programmation', 'description' => 'Bases de la logique algorithmique et structures de données', 'is_active' => true],
            ['id' => 12, 'code' => 'WEB_DEV', 'name' => 'Développement Web Front-End', 'description' => 'HTML5, CSS3, JavaScript, frameworks modernes', 'is_active' => true],
            ['id' => 13, 'code' => 'BACK_DEV', 'name' => 'Développement Back-End & API', 'description' => 'Conception d\'API REST, architectures serveur et frameworks', 'is_active' => true],
            ['id' => 14, 'code' => 'MOBILE_DEV', 'name' => 'Développement Mobile', 'description' => 'Applications hybrides et natives (Flutter, Android, iOS)', 'is_active' => true],
            ['id' => 15, 'code' => 'DATABASE_SYS', 'name' => 'Bases de Données & SGBD', 'description' => 'Modélisation relationnelle (UML/MERISE), SQL et NoSQL', 'is_active' => true],

            // --- Réseaux & Systèmes ---
            ['id' => 16, 'code' => 'NET_FUND', 'name' => 'Fondamentaux des Réseaux', 'description' => 'Modèle OSI, TCP/IP, adressage IPv4/IPv6', 'is_active' => true],
            ['id' => 17, 'code' => 'NET_ROUTING', 'name' => 'Routage & Commutation', 'description' => 'Configuration des switchs, routeurs et VLANs', 'is_active' => true],
            ['id' => 18, 'code' => 'SYS_ADMIN', 'name' => 'Administration Systèmes (Linux / Windows)', 'description' => 'Gestion des serveurs, utilisateurs, services et scripts', 'is_active' => true],
            ['id' => 19, 'code' => 'CYBER_SEC', 'name' => 'Introduction à la Cybersécurité', 'description' => 'Principes de sécurité, pare-feu, VPN et cryptographie', 'is_active' => true],

            // --- Gestion, Droit & Soft Skills ---
            ['id' => 20, 'code' => 'PROJECT_MGT', 'name' => 'Gestion de Projet Informatique', 'description' => 'Méthodes agiles (Scrum, Kanban) et planification', 'is_active' => true],
            ['id' => 21, 'code' => 'ENT_ECONOMY', 'name' => 'Économie & Droit du Numérique', 'description' => 'Droit des affaires, propriété intellectuelle et RGPD', 'is_active' => true],
        ];

        $now = now();
        foreach ($subjects as &$item) {
            $item['created_at'] = $now;
            $item['updated_at'] = $now;
        }

        DB::table('subjects')->upsert(
            $subjects,
            ['id'],
            ['code', 'name', 'description', 'is_active', 'updated_at']
        );
    }
}