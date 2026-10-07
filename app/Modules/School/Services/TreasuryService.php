<?php

namespace App\Modules\School\Services;

use App\Modules\School\Models\AccountingJournal;
use App\Modules\School\Models\ChartOfAccount;
use App\Modules\School\Models\FiscalYear;
use Illuminate\Support\Facades\DB;
use Exception;

class TreasuryService
{
    /**
     * Enregistrer un encaissement (Règlement Client / Scolarité)
     */
    public function recordReceipt(array $data)
    {
        return DB::transaction(function () use ($data) {
            // 1. Contrôle strict des paramètres
            if (empty($data['treasury_account_id'])) {
                throw new Exception("Le compte de trésorerie (52/57) est manquant pour le journal sélectionné.");
            }

            if (empty($data['third_party_account_id'])) {
                throw new Exception("Le compte tiers client (411) est introuvable dans le plan comptable.");
            }

            $journal = AccountingJournal::findOrFail($data['journal_id']);

            // 2. Recherche tolérante de l'exercice comptable (priorité à 'open')
            $fiscalYear = FiscalYear::where('status', 'open')->first() 
                ?? FiscalYear::latest()->first();

            if (!$fiscalYear) {
                throw new Exception("Aucun exercice comptable n'est configuré dans la base de données.");
            }

            $entryNumber = $data['reference'] ?? $this->generateEntryNumber($journal->code);

            // 3. Création de la pièce comptable
            $entryId = DB::table('accounting_entries')->insertGetId([
                'fiscal_year_id' => $fiscalYear->id,
                'journal_id'     => $journal->id,
                'entry_number'   => $entryNumber,
                'entry_date'     => $data['date'] ?? now()->toDateString(),
                'label'          => $data['label'] ?? 'Encaissement scolarité',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // 4. Inscription du Débit (Compte Trésorerie) et du Crédit (Compte Client)
            DB::table('accounting_entry_items')->insert([
                [
                    'accounting_entry_id' => $entryId,
                    'chart_of_account_id' => $data['treasury_account_id'],
                    'debit'               => $data['amount'],
                    'credit'              => 0,
                    'created_at'          => now(),
                    'updated_at'          => now(),
                ],
                [
                    'accounting_entry_id' => $entryId,
                    'chart_of_account_id' => $data['third_party_account_id'],
                    'debit'               => 0,
                    'credit'              => $data['amount'],
                    'created_at'          => now(),
                    'updated_at'          => now(),
                ]
            ]);

            return $entryId;
        });
    }

    /**
     * Extourner / Annuler un encaissement
     */
    public function reverseReceipt(array $data)
    {
        return DB::transaction(function () use ($data) {
            if (empty($data['treasury_account_id']) || empty($data['third_party_account_id'])) {
                throw new Exception("Paramètres de comptes invalides pour l'annulation.");
            }

            $journal = AccountingJournal::findOrFail($data['journal_id']);
            $fiscalYear = FiscalYear::where('status', 'open')->first() 
                ?? FiscalYear::latest()->first();

            if (!$fiscalYear) {
                throw new Exception("Aucun exercice comptable ouvert pour enregistrer l'extourne.");
            }

            $entryNumber = $data['reference'] ?? $this->generateEntryNumber($journal->code);

            $entryId = DB::table('accounting_entries')->insertGetId([
                'fiscal_year_id' => $fiscalYear->id,
                'journal_id'     => $journal->id,
                'entry_number'   => $entryNumber,
                'entry_date'     => $data['date'] ?? now()->toDateString(),
                'label'          => $data['label'] ?? 'Annulation encaissement',
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            // Débit : Compte Tiers (411) / Crédit : Compte Trésorerie
            DB::table('accounting_entry_items')->insert([
                [
                    'accounting_entry_id' => $entryId,
                    'chart_of_account_id' => $data['third_party_account_id'],
                    'debit'               => $data['amount'],
                    'credit'              => 0,
                    'created_at'          => now(),
                    'updated_at'          => now(),
                ],
                [
                    'accounting_entry_id' => $entryId,
                    'chart_of_account_id' => $data['treasury_account_id'],
                    'debit'               => 0,
                    'credit'              => $data['amount'],
                    'created_at'          => now(),
                    'updated_at'          => now(),
                ]
            ]);

            return $entryId;
        });
    }

    /**
     * Transfert de Fonds (Caisse -> Banque)
     */
    public function transferCashToBank(float $amount, int $cashAccountId, int $bankAccountId, string $date, string $label)
    {
        return DB::transaction(function () use ($amount, $cashAccountId, $bankAccountId, $date, $label) {
            $transitAccount = ChartOfAccount::where('code', 'LIKE', '585%')->firstOrFail();
            $fiscalYear     = FiscalYear::where('status', 'open')->first() ?? FiscalYear::latest()->firstOrFail();
            
            $cashJournal = AccountingJournal::where('type', 'cash')->firstOrFail();
            $bankJournal = AccountingJournal::where('type', 'bank')->firstOrFail();

            // 1. Sortie de Caisse
            $entryCashId = DB::table('accounting_entries')->insertGetId([
                'fiscal_year_id' => $fiscalYear->id,
                'journal_id'     => $cashJournal->id,
                'entry_number'   => $this->generateEntryNumber($cashJournal->code),
                'entry_date'     => $date,
                'label'          => 'Transfert vers Banque - ' . $label,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            DB::table('accounting_entry_items')->insert([
                ['accounting_entry_id' => $entryCashId, 'chart_of_account_id' => $transitAccount->id, 'debit' => $amount, 'credit' => 0, 'created_at' => now(), 'updated_at' => now()],
                ['accounting_entry_id' => $entryCashId, 'chart_of_account_id' => $cashAccountId, 'debit' => 0, 'credit' => $amount, 'created_at' => now(), 'updated_at' => now()],
            ]);

            // 2. Entrée en Banque
            $entryBankId = DB::table('accounting_entries')->insertGetId([
                'fiscal_year_id' => $fiscalYear->id,
                'journal_id'     => $bankJournal->id,
                'entry_number'   => $this->generateEntryNumber($bankJournal->code),
                'entry_date'     => $date,
                'label'          => 'Dépôt d\'espèces Caisse - ' . $label,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);

            DB::table('accounting_entry_items')->insert([
                ['accounting_entry_id' => $entryBankId, 'chart_of_account_id' => $bankAccountId, 'debit' => $amount, 'credit' => 0, 'created_at' => now(), 'updated_at' => now()],
                ['accounting_entry_id' => $entryBankId, 'chart_of_account_id' => $transitAccount->id, 'debit' => 0, 'credit' => $amount, 'created_at' => now(), 'updated_at' => now()],
            ]);
        });
    }

    private function generateEntryNumber(string $journalCode): string
    {
        $year = date('Y');
        $count = DB::table('accounting_entries')
            ->join('accounting_journals', 'accounting_entries.journal_id', '=', 'accounting_journals.id')
            ->where('accounting_journals.code', $journalCode)
            ->whereYear('accounting_entries.entry_date', $year)
            ->count() + 1;

        return sprintf('%s-%s-%05d', $journalCode, $year, $count);
    }
}