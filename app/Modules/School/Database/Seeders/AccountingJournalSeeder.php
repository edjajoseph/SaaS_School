<?php

namespace App\Modules\School\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Modules\School\Models\ChartOfAccount;

class AccountingJournalSeeder extends Seeder
{
    public function run(): void
    {
        $bankAccount = ChartOfAccount::where('code', 'LIKE', '52%')->first();
        $cashAccount = ChartOfAccount::where('code', 'LIKE', '57%')->first();

        $journals = [
            [
                'code' => 'BQ',
                'name' => 'Journal de Banque',
                'type' => 'bank',
                'default_account_id' => $bankAccount?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'CA',
                'name' => 'Journal de Caisse',
                'type' => 'cash',
                'default_account_id' => $cashAccount?->id,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'VT',
                'name' => 'Journal des Ventes / Scolarité',
                'type' => 'sales',
                'default_account_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'AC',
                'name' => 'Journal des Achats',
                'type' => 'purchase',
                'default_account_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'OD',
                'name' => 'Journal des Opérations Diverses',
                'type' => 'general',
                'default_account_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($journals as $journal) {
            DB::table('accounting_journals')->updateOrInsert(
                ['code' => $journal['code']],
                $journal
            );
        }
    }
}