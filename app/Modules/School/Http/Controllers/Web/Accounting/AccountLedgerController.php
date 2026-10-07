<?php

namespace App\Modules\School\Http\Controllers\Web\Accounting;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\ChartOfAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AccountLedgerController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-01-01'));
        $endDate   = $request->input('end_date', date('Y-m-d'));
        $accountId = $request->input('chart_of_account_id');

        $accounts = ChartOfAccount::orderBy('code', 'asc')->get();
        $selectedAccount = $accountId ? ChartOfAccount::find($accountId) : null;
        $entries = collect();

        if ($selectedAccount) {
            $entries = DB::table('accounting_entry_items')
                ->join('accounting_entries', 'accounting_entry_items.accounting_entry_id', '=', 'accounting_entries.id')
                ->leftJoin('accounting_journals', 'accounting_entries.journal_id', '=', 'accounting_journals.id')
                ->where('accounting_entry_items.chart_of_account_id', $selectedAccount->id)
                ->whereBetween('accounting_entries.entry_date', [$startDate, $endDate])
                ->select(
                    'accounting_entries.entry_date',
                    'accounting_entries.entry_number',
                    'accounting_entries.label as entry_label',
                    'accounting_journals.code as journal_code',
                    'accounting_entry_items.debit',
                    'accounting_entry_items.credit'
                )
                ->orderBy('accounting_entries.entry_date', 'asc')
                ->get();
        }

        return view('School::accounting.ledger.index', compact(
            'accounts',
            'selectedAccount',
            'entries',
            'startDate',
            'endDate'
        ));
    }

        public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-01-01'));
        $endDate   = $request->input('end_date', date('Y-m-d'));
        $accountId = $request->input('chart_of_account_id');

        $selectedAccount = $accountId ? ChartOfAccount::findOrFail($accountId) : null;
        
        $entries = DB::table('accounting_entry_items')
            ->join('accounting_entries', 'accounting_entry_items.accounting_entry_id', '=', 'accounting_entries.id')
            ->leftJoin('accounting_journals', 'accounting_entries.journal_id', '=', 'accounting_journals.id')
            ->where('accounting_entry_items.chart_of_account_id', $selectedAccount->id)
            ->whereBetween('accounting_entries.entry_date', [$startDate, $endDate])
            ->select(
                'accounting_entries.entry_date',
                'accounting_entries.entry_number',
                'accounting_entries.label as entry_label',
                'accounting_journals.code as journal_code',
                'accounting_entry_items.debit',
                'accounting_entry_items.credit'
            )
            ->orderBy('accounting_entries.entry_date', 'asc')
            ->get();

        $pdf = PDF::loadView('School::accounting.ledger.pdf', compact('selectedAccount', 'entries', 'startDate', 'endDate'));
        return $pdf->download("Grand_Livre_{$selectedAccount->code}.pdf");
    }
}