<?php

namespace App\Modules\School\Http\Controllers\Web\Accounting;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\ChartOfAccount;
use App\Modules\School\Models\AccountingEntryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class BankReconciliationController extends Controller
{
    public function index(Request $request)
    {
        $bankAccountId   = $request->input('chart_of_account_id');
        $bankStatementBal = $request->input('bank_statement_balance', 0);

        // Comptes de banque (Classe 52)
        $bankAccounts = ChartOfAccount::where('code', 'LIKE', '52%')->get();
        
        $bookBalance = 0;
        $unreconciledEntries = collect();

        if ($bankAccountId) {
            // Solde comptable du compte
            $mov = DB::table('accounting_entry_items')
                ->where('chart_of_account_id', $bankAccountId)
                ->selectRaw('SUM(debit - credit) as balance')
                ->first();

            $bookBalance = $mov->balance ?? 0;

            // Écritures non rapprochées (hypothèse: colonne is_reconciled existe ou à ajouter)
            $unreconciledEntries = DB::table('accounting_entry_items')
                ->join('accounting_entries', 'accounting_entry_items.accounting_entry_id', '=', 'accounting_entries.id')
                ->where('accounting_entry_items.chart_of_account_id', $bankAccountId)
                ->where('accounting_entry_items.is_reconciled', false)
                ->select(
                    'accounting_entry_items.id', 
                    'accounting_entries.entry_date', 
                    'accounting_entries.label', 
                    'accounting_entry_items.debit', 
                    'accounting_entry_items.credit'
                )
                ->get();
        }

        $gap = $bankStatementBal - $bookBalance;

        return view('School::accounting.bank_reconciliation.index', compact(
            'bankAccounts', 'bankAccountId', 'bankStatementBal', 'bookBalance', 'gap', 'unreconciledEntries'
        ));
    }

    /**
     * Valide le rapprochement de plusieurs lignes sélectionnées
     */
    public function reconcile(Request $request)
    {
        $request->validate([
            'item_ids'   => 'required|array',
            'item_ids.*' => 'exists:accounting_entry_items,id',
        ]);

        AccountingEntryItem::whereIn('id', $request->input('item_ids'))
            ->update([
                'is_reconciled' => true,
                'reconciled_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Les écritures sélectionnées ont été rapprochées avec succès.');
    }

    public function exportPdf(Request $request)
    {
        $bankAccountId   = $request->input('chart_of_account_id');
        $bankStatementBal = $request->input('bank_statement_balance', 0);
        $bankAccount     = ChartOfAccount::findOrFail($bankAccountId);

        $mov = DB::table('accounting_entry_items')
            ->where('chart_of_account_id', $bankAccountId)
            ->selectRaw('SUM(debit - credit) as balance')->first();

        $bookBalance = $mov->balance ?? 0;
        $gap         = $bankStatementBal - $bookBalance;

        $unreconciledEntries = DB::table('accounting_entry_items')
            ->join('accounting_entries', 'accounting_entry_items.accounting_entry_id', '=', 'accounting_entries.id')
            ->where('accounting_entry_items.chart_of_account_id', $bankAccountId)
            ->where('accounting_entry_items.is_reconciled', false)
            ->select(
                'accounting_entry_items.id', 
                'accounting_entries.entry_date', 
                'accounting_entries.label', 
                'accounting_entry_items.debit', 
                'accounting_entry_items.credit'
            )
            ->get();

        $pdf = PDF::loadView('School::accounting.bank_reconciliation.pdf', compact('bankAccount', 'bankStatementBal', 'bookBalance', 'gap', 'unreconciledEntries'));
        return $pdf->download("Etat_Rapprochement_Bancaire_{$bankAccount->code}.pdf");
    }
}