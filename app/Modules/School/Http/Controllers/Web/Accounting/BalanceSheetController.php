<?php

namespace App\Modules\School\Http\Controllers\Web\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class BalanceSheetController extends Controller
{
    public function index(Request $request)
    {
        $asOfDate = $request->input('as_of_date', date('Y-m-d'));

        // Actif (Classes 2, 3, 4 débiteur, 5 débiteur)
        $assets = DB::table('chart_of_accounts')
            ->where(function($q) {
                $q->where('code', 'LIKE', '2%')
                  ->orWhere('code', 'LIKE', '3%')
                  ->orWhere('code', 'LIKE', '4%')
                  ->orWhere('code', 'LIKE', '5%');
            })
            ->leftJoin('accounting_entry_items', 'chart_of_accounts.id', '=', 'accounting_entry_items.chart_of_account_id')
            ->leftJoin('accounting_entries', function($join) use ($asOfDate) {
                $join->on('accounting_entry_items.accounting_entry_id', '=', 'accounting_entries.id')
                     ->where('accounting_entries.entry_date', '<=', $asOfDate);
            })
            ->select('chart_of_accounts.code', 'chart_of_accounts.label', 
                DB::raw('SUM(accounting_entry_items.debit - accounting_entry_items.credit) as balance'))
            ->groupBy('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.label')
            ->having('balance', '>', 0)
            ->get();

        // Passif (Classes 1, 4 créditeur, 5 créditeur)
        $liabilities = DB::table('chart_of_accounts')
            ->where(function($q) {
                $q->where('code', 'LIKE', '1%')
                  ->orWhere('code', 'LIKE', '4%')
                  ->orWhere('code', 'LIKE', '5%');
            })
            ->leftJoin('accounting_entry_items', 'chart_of_accounts.id', '=', 'accounting_entry_items.chart_of_account_id')
            ->leftJoin('accounting_entries', function($join) use ($asOfDate) {
                $join->on('accounting_entry_items.accounting_entry_id', '=', 'accounting_entries.id')
                     ->where('accounting_entries.entry_date', '<=', $asOfDate);
            })
            ->select('chart_of_accounts.code', 'chart_of_accounts.label', 
                DB::raw('SUM(accounting_entry_items.credit - accounting_entry_items.debit) as balance'))
            ->groupBy('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.label')
            ->having('balance', '>', 0)
            ->get();

        $totalAssets      = $assets->sum('balance');
        $totalLiabilities = $liabilities->sum('balance');

        return view('School::accounting.balance_sheet.index', compact(
            'assets', 'liabilities', 'totalAssets', 'totalLiabilities', 'asOfDate'
        ));
    }

    public function exportPdf(Request $request)
    {
        $asOfDate = $request->input('as_of_date', date('Y-m-d'));

        $assets = DB::table('chart_of_accounts')
            ->where(function($q) {
                $q->where('code', 'LIKE', '2%')->orWhere('code', 'LIKE', '3%')->orWhere('code', 'LIKE', '4%')->orWhere('code', 'LIKE', '5%');
            })
            ->leftJoin('accounting_entry_items', 'chart_of_accounts.id', '=', 'accounting_entry_items.chart_of_account_id')
            ->leftJoin('accounting_entries', function($join) use ($asOfDate) {
                $join->on('accounting_entry_items.accounting_entry_id', '=', 'accounting_entries.id')
                    ->where('accounting_entries.entry_date', '<=', $asOfDate);
            })
            ->select('chart_of_accounts.code', 'chart_of_accounts.label', DB::raw('SUM(accounting_entry_items.debit - accounting_entry_items.credit) as balance'))
            ->groupBy('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.label')
            ->having('balance', '>', 0)->get();

        $liabilities = DB::table('chart_of_accounts')
            ->where(function($q) {
                $q->where('code', 'LIKE', '1%')->orWhere('code', 'LIKE', '4%')->orWhere('code', 'LIKE', '5%');
            })
            ->leftJoin('accounting_entry_items', 'chart_of_accounts.id', '=', 'accounting_entry_items.chart_of_account_id')
            ->leftJoin('accounting_entries', function($join) use ($asOfDate) {
                $join->on('accounting_entry_items.accounting_entry_id', '=', 'accounting_entries.id')
                    ->where('accounting_entries.entry_date', '<=', $asOfDate);
            })
            ->select('chart_of_accounts.code', 'chart_of_accounts.label', DB::raw('SUM(accounting_entry_items.credit - accounting_entry_items.debit) as balance'))
            ->groupBy('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.label')
            ->having('balance', '>', 0)->get();

        $totalAssets      = $assets->sum('balance');
        $totalLiabilities = $liabilities->sum('balance');

        $pdf = PDF::loadView('School::accounting.balance_sheet.pdf', compact('assets', 'liabilities', 'totalAssets', 'totalLiabilities', 'asOfDate'));
        return $pdf->download("Bilan_Comptable_au_{$asOfDate}.pdf");
    }
}