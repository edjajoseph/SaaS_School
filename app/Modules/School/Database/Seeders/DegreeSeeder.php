<?php

namespace App\Modules\School\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DegreeSeeder extends Seeder
{
    public function run(): void
    {
        $degrees = [
            // ==========================================
            // 1. ENSEIGNEMENT GÉNÉRAL
            // ==========================================
            [
                'code'      => 'CEPE',
                'name'      => 'Certificat d\'Étude Primaire et Élémentaire',
                'level'     => 'Primaire',
                'is_active' => true,
            ],
            [
                'code'      => 'BEPC',
                'name'      => 'Brevet d\'Études du Premier Cycle',
                'level'     => 'Secondaire 1er Cycle',
                'is_active' => true,
            ],
            [
                'code'      => 'BAC',
                'name'      => 'Baccalauréat Général',
                'level'     => 'Niveau Bac (Bac+0)',
                'is_active' => true,
            ],

            // ==========================================
            // 2. FORMATION TECHNIQUE ET PROFESSIONNELLE
            // ==========================================
            [
                'code'      => 'CAP',
                'name'      => 'Certificat d\'Aptitude Professionnelle',
                'level'     => 'Niveau BEPC / Qualification',
                'is_active' => true,
            ],
            [
                'code'      => 'BEP',
                'name'      => 'Brevet d\'Études Professionnelles',
                'level'     => 'Niveau Secondaire Technique',
                'is_active' => true,
            ],
            [
                'code'      => 'BT',
                'name'      => 'Brevet de Technicien',
                'level'     => 'Équivalent Bac Technique',
                'is_active' => true,
            ],
            [
                'code'      => 'BP',
                'name'      => 'Brevet Professionnel',
                'level'     => 'Post-CAP / Maîtrise',
                'is_active' => true,
            ],
            [
                'code'      => 'BAC_TECH',
                'name'      => 'Baccalauréat Technique et Industrialisé',
                'level'     => 'Niveau Bac (Bac+0)',
                'is_active' => true,
            ],

            // ==========================================
            // 3. CERTIFICATIONS PROFESSIONNELLES
            // ==========================================
            [
                'code'      => 'CQP',
                'name'      => 'Certificat de Qualification Professionnelle',
                'level'     => 'Certification Métier',
                'is_active' => true,
            ],
            [
                'code'      => 'CQM',
                'name'      => 'Certificat de Qualification aux Métiers',
                'level'     => 'Certification Métier / Artisanat',
                'is_active' => true,
            ],
            [
                'code'      => 'CERTIF_PROF',
                'name'      => 'Certificat de Compétence Professionnelle',
                'level'     => 'Formation Continue / Exécutive',
                'is_active' => true,
            ],

            // ==========================================
            // 4. ENSEIGNEMENT SUPÉRIEUR (LMD & GRANDES ÉCOLES)
            // ==========================================
            [
                'code'      => 'BTS',
                'name'      => 'Brevet de Technicien Supérieur',
                'level'     => 'Bac+2',
                'is_active' => true,
            ],
            [
                'code'      => 'DUT',
                'name'      => 'Diplôme Universitaire de Technologie',
                'level'     => 'Bac+2',
                'is_active' => true,
            ],
            [
                'code'      => 'LICENCE',
                'name'      => 'Licence / Bachelor',
                'level'     => 'Bac+3',
                'is_active' => true,
            ],
            [
                'code'      => 'LICENCE_PRO',
                'name'      => 'Licence Professionnelle',
                'level'     => 'Bac+3',
                'is_active' => true,
            ],
            [
                'code'      => 'MASTER',
                'name'      => 'Master / Magistère',
                'level'     => 'Bac+5',
                'is_active' => true,
            ],
            [
                'code'      => 'INGENIEUR',
                'name'      => 'Diplôme d\'Ingénieur d\'État',
                'level'     => 'Bac+5',
                'is_active' => true,
            ],
            [
                'code'      => 'MBA',
                'name'      => 'Master of Business Administration (Executive MBA)',
                'level'     => 'Bac+5 / Post-Experience',
                'is_active' => true,
            ],
            [
                'code'      => 'DOCTORAT',
                'name'      => 'Doctorat / Ph.D',
                'level'     => 'Bac+8',
                'is_active' => true,
            ],
        ];

        $now = now();
        foreach ($degrees as &$item) {
            $item['created_at'] = $now;
            $item['updated_at'] = $now;
        }

        // Upsert basé sur le champ unique 'code' (évite les conflits d'IDs incrémentaux)
        DB::table('degrees')->upsert(
            $degrees,
            ['code'],
            ['name', 'level', 'is_active', 'updated_at']
        );
    }
}