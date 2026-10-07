<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Solution;
use Illuminate\Database\Seeder;

class PlansTableSeeder extends Seeder
{
    public function run(): void
    {
        // Récupération de la solution Hôtel
        $hotelSolution = Solution::where('code', 'hotel')->first();

        if (!$hotelSolution) {
            $this->command->error('La solution "hotel" est introuvable. Veillez à exécuter SolutionsTableSeeder en premier.');
            return;
        }

        $plans = [
            [
                'solution_id'       => $hotelSolution->id,
                'name'              => 'Pack Starter (Hôtel)',
                'code'              => 'hotel-starter',
                'price'             => 25000.00,
                'currency'          => 'XOF',
                'invoice_period'    => 1,
                'invoice_interval'  => 'month',
                'trial_period_days' => 14,
                'is_active'         => true,
            ],
            [
                'solution_id'       => $hotelSolution->id,
                'name'              => 'Pack Pro (Hôtel)',
                'code'              => 'hotel-pro',
                'price'             => 60000.00,
                'currency'          => 'XOF',
                'invoice_period'    => 1,
                'invoice_interval'  => 'month',
                'trial_period_days' => 14,
                'is_active'         => true,
            ],
            [
                'solution_id'       => $hotelSolution->id,
                'name'              => 'Pack Enterprise Annuel (Hôtel)',
                'code'              => 'hotel-enterprise-annual',
                'price'             => 600000.00,
                'currency'          => 'XOF',
                'invoice_period'    => 1,
                'invoice_interval'  => 'year',
                'trial_period_days' => 30,
                'is_active'         => true,
            ],
        ];

        foreach ($plans as $planData) {
            Plan::updateOrCreate(
                ['code' => $planData['code']],
                $planData
            );
        }

        $this->command->info('Plans tarifaires créés avec succès !');
    }
}