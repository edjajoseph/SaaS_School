<?php

namespace App\Modules\School\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CycleSeeder extends Seeder
{
    public function run(): void
    {
        $cycles = [
            // --- ENSEIGNEMENT DE BASE ---
            [
                'code'           => 'PRE',
                'name'           => 'Preschool',
                'sequence_order' => 1,
                'description'    => 'Early Childhood Education / Éducation Préscolaire',
                'is_active'      => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'code'           => 'PRI',
                'name'           => 'Primary School',
                'sequence_order' => 2,
                'description'    => 'Primary / Elementary Education (CI, CP, CE, CM)',
                'is_active'      => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],

            // --- SECONDAIRE GÉNÉRAL & TECHNIQUE ---
            [
                'code'           => 'SEC_GEN',
                'name'           => 'General Secondary',
                'sequence_order' => 3,
                'description'    => 'General Secondary Education (Collège & Lycée Général)',
                'is_active'      => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'code'           => 'SEC_TECH',
                'name'           => 'Technical Secondary',
                'sequence_order' => 4,
                'description'    => 'Technical Secondary Education (Lycée Technique)',
                'is_active'      => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],

            // --- FORMATION PROFESSIONNELLE ---
            [
                'code'           => 'VOC_QUALIF',
                'name'           => 'Vocational Qualification',
                'sequence_order' => 5,
                'description'    => 'Qualifying Vocational Training / Formations qualifiantes courtes (CQC, Certifications, Attestations)',
                'is_active'      => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'code'           => 'VOC_DIPLOMA',
                'name'           => 'Vocational Diploma',
                'sequence_order' => 6,
                'description'    => 'Diploming Vocational Training / Formations diplômantes (CAP, BEP, BT, BP)',
                'is_active'      => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],

            // --- ENSEIGNEMENT SUPÉRIEUR ---
            [
                'code'           => 'HIGHER_SHORT',
                'name'           => 'Short Higher Education',
                'sequence_order' => 7,
                'description'    => 'BTS, DUT, Associate Degrees (BAC+2)',
                'is_active'      => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'code'           => 'HIGHER_BACHELOR',
                'name'           => 'Higher Education - Bachelor / Licence',
                'sequence_order' => 8,
                'description'    => 'Undergraduate Studies / Cycle Licence (L1, L2, L3)',
                'is_active'      => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'code'           => 'HIGHER_MASTER',
                'name'           => 'Higher Education - Master / Engineering',
                'sequence_order' => 9,
                'description'    => 'Postgraduate Studies / Cycle Master et Ingénieur (M1, M2)',
                'is_active'      => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
            [
                'code'           => 'HIGHER_DOCTORATE',
                'name'           => 'Higher Education - Doctorate / PhD',
                'sequence_order' => 10,
                'description'    => 'Postgraduate Research / Cycle Doctoral',
                'is_active'      => true,
                'created_at'     => now(),
                'updated_at'     => now(),
            ],
        ];

        foreach ($cycles as $cycle) {
            DB::table('cycles')->updateOrInsert(
                ['code' => $cycle['code']],
                $cycle
            );
        }
    }
}