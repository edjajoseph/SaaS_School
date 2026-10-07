<?php

namespace App\Modules\School\Services;

use  App\Modules\School\Models\Payment;
use  App\Modules\School\Models\StudentFeeAccount;
use App\Modules\School\Models\AccountingEntry;
use App\Modules\School\Models\ChartOfAccount;
use App\Modules\School\Models\FiscalYear;
use App\Modules\School\Models\Journal;
use Illuminate\Support\Facades\DB;

class AccountingService
{
    /**
     * Génère l'écriture de prise en charge de la créance d'un élève (Journal des Ventes / VT)
     * Débit 411100 (Compte Élève) | Débit 658000 (Remise) = Crédit 706100 (Frais Scolaires)
     */
    public function recordFeeAccountInvoice(StudentFeeAccount $account): AccountingEntry
    {
        return DB::transaction(function () use ($account) {
            $fiscalYear = FiscalYear::where('status', 'open')->firstOrFail();
            $journal = Journal::where('code', 'VT')->firstOrFail();

            $accountClient = ChartOfAccount::where('code', '411100')->firstOrFail();
            $accountRevenue = ChartOfAccount::where('code', '706100')->firstOrFail();
            $accountDiscount = ChartOfAccount::where('code', '658000')->firstOrFail();

            $studentName = optional($account->registration->student)->first_name . ' ' . optional($account->registration->student)->last_name;

            $entry = AccountingEntry::create([
                'fiscal_year_id' => $fiscalYear->id,
                'journal_id'     => $journal->id,
                'entry_number'   => 'VT-' . date('Ym') . '-' . str_pad($account->id, 5, '0', STR_PAD_LEFT),
                'entry_date'     => now()->toDateString(),
                'label'          => "Prise en charge scolarité - " . $studentName,
                'source_type'    => StudentFeeAccount::class,
                'source_id'      => $account->id,
            ]);

            // 1. Débit : Créance Élève Net à Payer
            $entry->items()->create([
                'chart_of_account_id' => $accountClient->id,
                'label'               => "Créance scolarité " . $studentName,
                'debit'               => max(0, $account->total_due - $account->discount_amount),
                'credit'              => 0,
            ]);

            // 2. Débit : Remises / Bourses accordées (si applicables)
            if ($account->discount_amount > 0) {
                $entry->items()->create([
                    'chart_of_account_id' => $accountDiscount->id,
                    'label'               => "Remise/Bourse accordée - " . $account->discount_reason,
                    'debit'               => $account->discount_amount,
                    'credit'              => 0,
                ]);
            }

            // 3. Crédit : Produits des prestations d'enseignement (Montant Brut)
            $entry->items()->create([
                'chart_of_account_id' => $accountRevenue->id,
                'label'               => "Produits scolarités bruts",
                'debit'               => 0,
                'credit'              => $account->total_due,
            ]);

            return $entry;
        });
    }

    /**
     * Génère l'écriture lors de la réception d'un règlement (Journal de Caisse / CA ou Banque / BQ)
     * Débit 571100 (Caisse) / 521100 (Banque) | Crédit 411100 (Compte Élève)
     */
    public function recordPaymentEntry(Payment $payment): AccountingEntry
    {
        return DB::transaction(function () use ($payment) {
            $fiscalYear = FiscalYear::where('status', 'open')->firstOrFail();
            
            $journalCode = $payment->payment_method === 'cash' ? 'CA' : 'BQ';
            $journal = Journal::where('code', $journalCode)->firstOrFail();

            $cashBankCode = match ($payment->payment_method) {
                'cash'          => '571100', // Caisse
                'bank_transfer' => '521100', // Banque
                'mobile_money'  => '571200', // Portefeuille Mobile
                default         => '521100',
            };

            $accountTreasury = ChartOfAccount::where('code', $cashBankCode)->firstOrFail();
            $accountClient   = ChartOfAccount::where('code', '411100')->firstOrFail();

            $studentName = optional($payment->account->registration->student)->first_name . ' ' . optional($payment->account->registration->student)->last_name;

            $entry = AccountingEntry::create([
                'fiscal_year_id' => $fiscalYear->id,
                'journal_id'     => $journal->id,
                'entry_number'   => $journalCode . '-' . date('Ym') . '-' . str_pad($payment->id, 5, '0', STR_PAD_LEFT),
                'entry_date'     => $payment->paid_at ? $payment->paid_at->format('Y-m-d') : now()->toDateString(),
                'label'          => "Règlement reçu - " . $payment->receipt_number . " - " . $studentName,
                'source_type'    => Payment::class,
                'source_id'      => $payment->id,
            ]);

            // 1. Débit : Trésorerie (Caisse / Banque / Mobile Money)
            $entry->items()->create([
                'chart_of_account_id' => $accountTreasury->id,
                'label'               => "Encaissement " . $payment->receipt_number,
                'debit'               => $payment->amount,
                'credit'              => 0,
            ]);

            // 2. Crédit : Extinction de la créance Élève (Permet le lettrage)
            $entry->items()->create([
                'chart_of_account_id' => $accountClient->id,
                'label'               => "Règlement scolarité " . $studentName,
                'debit'               => 0,
                'credit'              => $payment->amount,
            ]);

            return $entry;
        });
    }
}