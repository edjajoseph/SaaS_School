<?php

namespace App\Modules\School\Database\Seeders;

use App\Modules\School\Models\Cycle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    public function run(): void
    {
        // Récupération des IDs des cycles par leurs codes
        $cycles = Cycle::pluck('id', 'code');

        if ($cycles->isEmpty()) {
            $this->command->error('Veuillez exécuter CycleSeeder avant d\'exécuter LevelSeeder !');
            return;
        }

        $levels = [

            // ==========================================
            // 1. PRÉSCOLAIRE / MATERNELLE (PRE)
            // ==========================================
            [
                'cycle_id'       => $cycles['PRE'] ?? null,
                'code'           => 'TPS',
                'name'           => 'Toute Petite Section',
                'has_exam'       => false,
                'exam_name'      => null,
                'sequence_order' => 1,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['PRE'] ?? null,
                'code'           => 'PS',
                'name'           => 'Petite Section',
                'has_exam'       => false,
                'exam_name'      => null,
                'sequence_order' => 2,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['PRE'] ?? null,
                'code'           => 'MS',
                'name'           => 'Moyenne Section',
                'has_exam'       => false,
                'exam_name'      => null,
                'sequence_order' => 3,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['PRE'] ?? null,
                'code'           => 'GS',
                'name'           => 'Grande Section',
                'has_exam'       => false,
                'exam_name'      => null,
                'sequence_order' => 4,
                'is_active'      => true,
            ],

            // ==========================================
            // 2. PRIMAIRE (PRI) - CP1 à CM2
            // ==========================================
            [
                'cycle_id'       => $cycles['PRI'] ?? null,
                'code'           => 'CP1',
                'name'           => 'Cours Préparatoire 1ère année',
                'has_exam'       => false,
                'exam_name'      => null,
                'sequence_order' => 5,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['PRI'] ?? null,
                'code'           => 'CP2',
                'name'           => 'Cours Préparatoire 2ème année',
                'has_exam'       => false,
                'exam_name'      => null,
                'sequence_order' => 6,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['PRI'] ?? null,
                'code'           => 'CE1',
                'name'           => 'Cours Élémentaire 1ère année',
                'has_exam'       => false,
                'exam_name'      => null,
                'sequence_order' => 7,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['PRI'] ?? null,
                'code'           => 'CE2',
                'name'           => 'Cours Élémentaire 2ème année',
                'has_exam'       => false,
                'exam_name'      => null,
                'sequence_order' => 8,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['PRI'] ?? null,
                'code'           => 'CM1',
                'name'           => 'Cours Moyen 1ère année',
                'has_exam'       => false,
                'exam_name'      => null,
                'sequence_order' => 9,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['PRI'] ?? null,
                'code'           => 'CM2',
                'name'           => 'Cours Moyen 2ème année',
                'has_exam'       => true,
                'exam_name'      => 'CEPE',
                'sequence_order' => 10,
                'is_active'      => true,
            ],

            // ==========================================
            // 3. SECONDAIRE GÉNÉRAL (SEC_GEN) - 6ème à Tle
            // ==========================================
            // --- Premier Cycle ---
            [
                'cycle_id'       => $cycles['SEC_GEN'] ?? null,
                'code'           => '6EME',
                'name'           => 'Sixième',
                'has_exam'       => false,
                'exam_name'      => null,
                'sequence_order' => 11,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['SEC_GEN'] ?? null,
                'code'           => '5EME',
                'name'           => 'Cinquième',
                'has_exam'       => false,
                'exam_name'      => null,
                'sequence_order' => 12,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['SEC_GEN'] ?? null,
                'code'           => '4EME',
                'name'           => 'Quatrième',
                'has_exam'       => false,
                'exam_name'      => null,
                'sequence_order' => 13,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['SEC_GEN'] ?? null,
                'code'           => '3EME',
                'name'           => 'Troisième',
                'has_exam'       => true,
                'exam_name'      => 'BEPC',
                'sequence_order' => 14,
                'is_active'      => true,
            ],
            // --- Second Cycle ---
            [
                'cycle_id'       => $cycles['SEC_GEN'] ?? null,
                'code'           => '2ND_GEN',
                'name'           => 'Seconde Générale',
                'has_exam'       => false,
                'exam_name'      => null,
                'sequence_order' => 15,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['SEC_GEN'] ?? null,
                'code'           => '1ERE_GEN',
                'name'           => 'Première Générale',
                'has_exam'       => false,
                'exam_name'      => null,
                'sequence_order' => 16,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['SEC_GEN'] ?? null,
                'code'           => 'TLE_GEN',
                'name'           => 'Terminales Générale',
                'has_exam'       => true,
                'exam_name'      => 'BAC Général',
                'sequence_order' => 17,
                'is_active'      => true,
            ],

            // ==========================================
            // 4. SECONDAIRE TECHNIQUE (SEC_TECH)
            // ==========================================
            [
                'cycle_id'       => $cycles['SEC_TECH'] ?? null,
                'code'           => '2ND_TECH',
                'name'           => 'Seconde Technique',
                'has_exam'       => false,
                'exam_name'      => null,
                'sequence_order' => 18,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['SEC_TECH'] ?? null,
                'code'           => '1ERE_TECH',
                'name'           => 'Première Technique',
                'has_exam'       => false,
                'exam_name'      => null,
                'sequence_order' => 19,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['SEC_TECH'] ?? null,
                'code'           => 'TLE_TECH',
                'name'           => 'Terminales Technique',
                'has_exam'       => true,
                'exam_name'      => 'BAC Technique',
                'sequence_order' => 20,
                'is_active'      => true,
            ],

            // ==========================================
            // 5. FORMATION PROFESSIONNELLE QUALIFIANTE (VOC_QUALIF)
            // ==========================================
            [
                'cycle_id'       => $cycles['VOC_QUALIF'] ?? null,
                'code'           => 'CQC_1',
                'name'           => 'CQC (Certificat de Qualification)", 1ère année',
                'has_exam'       => false,
                'exam_name'      => null,
                'sequence_order' => 21,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['VOC_QUALIF'] ?? null,
                'code'           => 'CQC_2',
                'name'           => 'CQC (Certificat de Qualification), 2ème année',
                'has_exam'       => true,
                'exam_name'      => 'CQC',
                'sequence_order' => 22,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['VOC_QUALIF'] ?? null,
                'code'           => 'FC_MOD',
                'name'           => 'Formation Continue / Modulaire',
                'has_exam'       => true,
                'exam_name'      => 'Attestation / Certificat',
                'sequence_order' => 23,
                'is_active'      => true,
            ],

            // ==========================================
            // 6. FORMATION PROFESSIONNELLE DIPLÔMANTE (VOC_DIPLOMA)
            // ==========================================
            // --- CAP (Certificat d'Aptitude Professionnelle) ---
            [
                'cycle_id'       => $cycles['VOC_DIPLOMA'] ?? null,
                'code'           => 'CAP_1',
                'name'           => 'CAP 1ère année',
                'has_exam'       => false,
                'exam_name'      => null,
                'sequence_order' => 24,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['VOC_DIPLOMA'] ?? null,
                'code'           => 'CAP_2',
                'name'           => 'CAP 2ème année',
                'has_exam'       => true,
                'exam_name'      => 'CAP',
                'sequence_order' => 25,
                'is_active'      => true,
            ],
            // --- BEP (Brevet d'Études Professionnelles) ---
            [
                'cycle_id'       => $cycles['VOC_DIPLOMA'] ?? null,
                'code'           => 'BEP_1',
                'name'           => 'BEP 1ère année',
                'has_exam'       => false,
                'exam_name'      => null,
                'sequence_order' => 26,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['VOC_DIPLOMA'] ?? null,
                'code'           => 'BEP_2',
                'name'           => 'BEP 2ème année',
                'has_exam'       => true,
                'exam_name'      => 'BEP',
                'sequence_order' => 27,
                'is_active'      => true,
            ],
            // --- BT (Brevet Technicien) ---
            [
                'cycle_id'       => $cycles['VOC_DIPLOMA'] ?? null,
                'code'           => 'BT_1',
                'name'           => 'BT 1ère année',
                'has_exam'       => false,
                'exam_name'      => null,
                'sequence_order' => 28,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['VOC_DIPLOMA'] ?? null,
                'code'           => 'BT_2',
                'name'           => 'BT 2ème année',
                'has_exam'       => false,
                'exam_name'      => null,
                'sequence_order' => 29,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['VOC_DIPLOMA'] ?? null,
                'code'           => 'BT_3',
                'name'           => 'BT 3ème année',
                'has_exam'       => true,
                'exam_name'      => 'BT',
                'sequence_order' => 30,
                'is_active'      => true,
            ],
            // --- BP (Brevet Professionnel) ---
            [
                'cycle_id'       => $cycles['VOC_DIPLOMA'] ?? null,
                'code'           => 'BP_1',
                'name'           => 'BP 1ère année',
                'has_exam'       => false,
                'exam_name'      => null,
                'sequence_order' => 31,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['VOC_DIPLOMA'] ?? null,
                'code'           => 'BP_2',
                'name'           => 'BP 2ème année',
                'has_exam'       => true,
                'exam_name'      => 'BP',
                'sequence_order' => 32,
                'is_active'      => true,
            ],

            // ==========================================
            // 7. ENSEIGNEMENT SUPÉRIEUR COURT (HIGHER_SHORT)
            // ==========================================
            [
                'cycle_id'       => $cycles['HIGHER_SHORT'] ?? null,
                'code'           => 'BTS_1',
                'name'           => 'BTS 1ère année',
                'has_exam'       => false,
                'exam_name'      => null,
                'sequence_order' => 33,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['HIGHER_SHORT'] ?? null,
                'code'           => 'BTS_2',
                'name'           => 'BTS 2ème année',
                'has_exam'       => true,
                'exam_name'      => 'BTS',
                'sequence_order' => 34,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['HIGHER_SHORT'] ?? null,
                'code'           => 'DUT_1',
                'name'           => 'DUT 1ère année',
                'has_exam'       => false,
                'exam_name'      => null,
                'sequence_order' => 35,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['HIGHER_SHORT'] ?? null,
                'code'           => 'DUT_2',
                'name'           => 'DUT 2ème année',
                'has_exam'       => true,
                'exam_name'      => 'DUT',
                'sequence_order' => 36,
                'is_active'      => true,
            ],

            // ==========================================
            // 8. ENSEIGNEMENT SUPÉRIEUR - LICENCE (HIGHER_BACHELOR)
            // ==========================================
            [
                'cycle_id'       => $cycles['HIGHER_BACHELOR'] ?? null,
                'code'           => 'L1',
                'name'           => 'Licence 1 (BAC+1)',
                'has_exam'       => false,
                'exam_name'      => null,
                'sequence_order' => 37,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['HIGHER_BACHELOR'] ?? null,
                'code'           => 'L2',
                'name'           => 'Licence 2 (BAC+2)',
                'has_exam'       => false,
                'exam_name'      => null,
                'sequence_order' => 38,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['HIGHER_BACHELOR'] ?? null,
                'code'           => 'L3',
                'name'           => 'Licence 3 (BAC+3)',
                'has_exam'       => true,
                'exam_name'      => 'Diplôme de Licence',
                'sequence_order' => 39,
                'is_active'      => true,
            ],

            // ==========================================
            // 9. ENSEIGNEMENT SUPÉRIEUR - MASTER / INGÉNIEUR (HIGHER_MASTER)
            // ==========================================
            [
                'cycle_id'       => $cycles['HIGHER_MASTER'] ?? null,
                'code'           => 'M1',
                'name'           => 'Master 1 / Ingénieur 1ère année (BAC+4)',
                'has_exam'       => false,
                'exam_name'      => null,
                'sequence_order' => 40,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['HIGHER_MASTER'] ?? null,
                'code'           => 'M2',
                'name'           => 'Master 2 / Ingénieur 2ème année (BAC+5)',
                'has_exam'       => true,
                'exam_name'      => 'Diplôme de Master / Diplôme d\'Ingénieur',
                'sequence_order' => 41,
                'is_active'      => true,
            ],

            // ==========================================
            // 10. ENSEIGNEMENT SUPÉRIEUR - DOCTORAT (HIGHER_DOCTORATE)
            // ==========================================
            [
                'cycle_id'       => $cycles['HIGHER_DOCTORATE'] ?? null,
                'code'           => 'DOC_1',
                'name'           => 'Doctorat 1ère année (D1)',
                'has_exam'       => false,
                'exam_name'      => null,
                'sequence_order' => 42,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['HIGHER_DOCTORATE'] ?? null,
                'code'           => 'DOC_2',
                'name'           => 'Doctorat 2ème année (D2)',
                'has_exam'       => false,
                'exam_name'      => null,
                'sequence_order' => 43,
                'is_active'      => true,
            ],
            [
                'cycle_id'       => $cycles['HIGHER_DOCTORATE'] ?? null,
                'code'           => 'DOC_3',
                'name'           => 'Doctorat 3ème année (D3)',
                'has_exam'       => true,
                'exam_name'      => 'Thèse de Doctorat / PhD',
                'sequence_order' => 44,
                'is_active'      => true,
            ],
        ];

        foreach ($levels as $level) {
            if (!empty($level['cycle_id'])) {
                DB::table('levels')->updateOrInsert(
                    ['code' => $level['code']],
                    array_merge($level, [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ])
                );
            }
        }
    }
}