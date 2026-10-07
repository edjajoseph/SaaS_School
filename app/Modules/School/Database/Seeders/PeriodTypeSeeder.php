<?php

namespace App\Modules\School\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PeriodTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $periodTypes = [
            [
                'name' => 'Trimestriel',
                'code' => 'TRIMESTER',
                'description' => 'Découpage de l\'année académique en 3 ou 4 trimestres.',
                'items' => [
                    ['name' => 'Trimestre 1', 'code' => 'TRIM_1', 'order' => 1],
                    ['name' => 'Trimestre 2', 'code' => 'TRIM_2', 'order' => 2],
                    ['name' => 'Trimestre 3', 'code' => 'TRIM_3', 'order' => 3],
                    ['name' => 'Trimestre 4', 'code' => 'TRIM_4', 'order' => 4],
                ],
            ],
            [
                'name' => 'Semestriel',
                'code' => 'SEMESTER',
                'description' => 'Découpage de l\'année académique en 2 semestres.',
                'items' => [
                    ['name' => 'Semestre 1', 'code' => 'SEM_1', 'order' => 1],
                    ['name' => 'Semestre 2', 'code' => 'SEM_2', 'order' => 2],
                ],
            ],
            [
                'name' => 'Session / Module',
                'code' => 'SESSION',
                'description' => 'Découpage par sessions d\'apprentissage ou sessions d\'examens.',
                'items' => [
                    ['name' => 'Session 1', 'code' => 'SESS_1', 'order' => 1],
                    ['name' => 'Session 2', 'code' => 'SESS_2', 'order' => 2],
                    ['name' => 'Session 3', 'code' => 'SESS_3', 'order' => 3],
                ],
            ],
        ];

        foreach ($periodTypes as $typeData) {
            // Insertion ou mise à jour du type de période
            $periodTypeId = DB::table('period_types')->insertGetId([
                'name'        => $typeData['name'],
                'code'        => $typeData['code'],
                'description' => $typeData['description'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            // Insertion des items rattachés
            foreach ($typeData['items'] as $item) {
                DB::table('period_type_items')->insert([
                    'period_type_id' => $periodTypeId,
                    'name'           => $item['name'],
                    'code'           => $item['code'],
                    'sequence_order' => $item['order'],
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);
            }
        }
    }
}