<?php

namespace App\Modules\School\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SerieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seriesAndFilieres = [
            // ==========================================
            // 1. SECOND CYCLE (SÉRIES SECONDAIRES)
            // ==========================================
            [
                'code'        => 'SERIE_A1',
                'name'        => 'Série A1',
                'description' => 'Lettres et Langues Clairs/Classiques',
                'is_active'   => true,
            ],
            [
                'code'        => 'SERIE_A2',
                'name'        => 'Série A2',
                'description' => 'Lettres, Langues et Sciences Humaines',
                'is_active'   => true,
            ],
            [
                'code'        => 'SERIE_B',
                'name'        => 'Série B',
                'description' => 'Sciences Économiques et Sociales',
                'is_active'   => true,
            ],
            [
                'code'        => 'SERIE_C',
                'name'        => 'Série C',
                'description' => 'Mathématiques et Sciences Physiques',
                'is_active'   => true,
            ],
            [
                'code'        => 'SERIE_D',
                'name'        => 'Série D',
                'description' => 'Mathématiques et Sciences de la Vie et de la Terre',
                'is_active'   => true,
            ],
            [
                'code'        => 'SERIE_E',
                'name'        => 'Série E',
                'description' => 'Mathématiques et Technique',
                'is_active'   => true,
            ],
            [
                'code'        => 'SERIE_F1',
                'name'        => 'Série F1',
                'description' => 'Construction Mécanique',
                'is_active'   => true,
            ],
            [
                'code'        => 'SERIE_F2',
                'name'        => 'Série F2',
                'description' => 'Électronique',
                'is_active'   => true,
            ],
            [
                'code'        => 'SERIE_F3',
                'name'        => 'Série F3',
                'description' => 'Électrotechnique',
                'is_active'   => true,
            ],
            [
                'code'        => 'SERIE_F4',
                'name'        => 'Série F4',
                'description' => 'Génie Civil / Bâtiment',
                'is_active'   => true,
            ],
            [
                'code'        => 'SERIE_G1',
                'name'        => 'Série G1',
                'description' => 'Secrétariat et Bureautique',
                'is_active'   => true,
            ],
            [
                'code'        => 'SERIE_G2',
                'name'        => 'Série G2',
                'description' => 'Comptabilité et Gestion',
                'is_active'   => true,
            ],

            // ==========================================
            // 2. SUPÉRIEUR : BTS & TERTIAIRE
            // ==========================================
            [
                'code'        => 'BTS_IDA',
                'name'        => 'Informatique Développeur d\'Applications (IDA)',
                'description' => 'BTS - Analyse, conception et développement informatique',
                'is_active'   => true,
            ],
            [
                'code'        => 'BTS_RIT',
                'name'        => 'Réseaux Informatiques et Télécommunications (RIT)',
                'description' => 'BTS - Administration des réseaux et systèmes informatiques',
                'is_active'   => true,
            ],
            [
                'code'        => 'BTS_FCGE',
                'name'        => 'Finance Comptabilité et Gestion d\'Entreprises (FCGE)',
                'description' => 'BTS - Gestion comptable, financière et fiscale',
                'is_active'   => true,
            ],
            [
                'code'        => 'BTS_GEC',
                'name'        => 'Gestion Commerciale (GEC)',
                'description' => 'BTS - Technico-commercial, marketing et vente',
                'is_active'   => true,
            ],
            [
                'code'        => 'BTS_RHCOM',
                'name'        => 'Ressources Humaines et Communication (RHCOM)',
                'description' => 'BTS - Gestion du personnel et communication d\'entreprise',
                'is_active'   => true,
            ],
            [
                'code'        => 'BTS_LOG',
                'name'        => 'Logistique et Transport',
                'description' => 'BTS - Supply chain, gestion des stocks et transit',
                'is_active'   => true,
            ],
            [
                'code'        => 'BTS_AD',
                'name'        => 'Assistanat de Direction (AD)',
                'description' => 'BTS - Secrétariat de direction et organisation administrative',
                'is_active'   => true,
            ],

            // ==========================================
            // 3. SUPÉRIEUR : INFORMATIQUE & INGÉNIERIE (LMD)
            // ==========================================
            [
                'code'        => 'FIL_GL',
                'name'        => 'Génie Logiciel & Systèmes d\'Information',
                'description' => 'Licence / Master - Architecture logicielle, développement Web/Mobile & SaaS',
                'is_active'   => true,
            ],
            [
                'code'        => 'FIL_SRI',
                'name'        => 'Systèmes, Réseaux & Sécurité Informatique',
                'description' => 'Licence / Master - Administration système, cloud, VPN et cybersécurité',
                'is_active'   => true,
            ],
            [
                'code'        => 'FIL_IA_DATA',
                'name'        => 'Intelligence Artificielle & Data Science',
                'description' => 'Licence / Master - Analyse de données, Machine Learning et Big Data',
                'is_active'   => true,
            ],
            [
                'code'        => 'FIL_GC',
                'name'        => 'Génie Civil & Bâtiment',
                'description' => 'Licence / Master / Ingénieur - BTP, ouvrages d\'art et infrastructures',
                'is_active'   => true,
            ],
            [
                'code'        => 'FIL_GEEE',
                'name'        => 'Génie Électrique, Électronique & Énergie',
                'description' => 'Licence / Master / Ingénieur - Systèmes électriques et énergies renouvelables',
                'is_active'   => true,
            ],
            [
                'code'        => 'FIL_GEM',
                'name'        => 'Génie Électromécanique & Maintenance',
                'description' => 'Licence / Master / Ingénieur - Automatisme et maintenance industrielle',
                'is_active'   => true,
            ],

            // ==========================================
            // 4. SUPÉRIEUR : GESTION, DROIT & SCIENCES HUMAINES
            // ==========================================
            [
                'code'        => 'FIL_SEG',
                'name'        => 'Sciences Économiques & de Gestion',
                'description' => 'Licence / Master - Économie appliquée, finance et analyse de marché',
                'is_active'   => true,
            ],
            [
                'code'        => 'FIL_DROIT',
                'name'        => 'Droit Public & Privé',
                'description' => 'Licence / Master - Droit des affaires, droit public et relations internationales',
                'is_active'   => true,
            ],
            [
                'code'        => 'FIL_CCA',
                'name'        => 'Comptabilité, Contrôle & Audit (CCA)',
                'description' => 'Licence / Master - Expertise comptable, audit financier et contrôle de gestion',
                'is_active'   => true,
            ],
            [
                'code'        => 'FIL_COM_MARK',
                'name'        => 'Marketing & Communication Digitale',
                'description' => 'Licence / Master - Stratégie de marque, médias sociaux et e-commerce',
                'is_active'   => true,
            ],
            [
                'code'        => 'FIL_MIAGE',
                'name'        => 'Méthodes Informatiques Appliquées à la Gestion (MIAGE)',
                'description' => 'Licence / Master - Double compétence Informatique et Gestion d\'entreprise',
                'is_active'   => true,
            ],
        ];

        // Insertion optimisée avec mise à jour automatique
        foreach ($seriesAndFilieres as $item) {
            DB::table('series')->updateOrInsert(
                ['code' => $item['code']],
                array_merge($item, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}