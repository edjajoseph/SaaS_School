<?php

namespace App\Modules\School\Http\Controllers\Web\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class IncomeStatementController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-01-01'));
        $endDate   = $request->input('end_date', date('Y-m-d'));

        // Charges (Classe 6)
        $expenses = DB::table('chart_of_accounts')
            ->where('code', 'LIKE', '6%')
            ->leftJoin('accounting_entry_items', 'chart_of_accounts.id', '=', 'accounting_entry_items.chart_of_account_id')
            ->leftJoin('accounting_entries', function($join) use ($startDate, $endDate) {
                $join->on('accounting_entry_items.accounting_entry_id', '=', 'accounting_entries.id')
                     ->whereBetween('accounting_entries.entry_date', [$startDate, $endDate]);
            })
            ->select('chart_of_accounts.code', 'chart_of_accounts.label', 
                DB::raw('SUM(accounting_entry_items.debit - accounting_entry_items.credit) as amount'))
            ->groupBy('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.label')
            ->having('amount', '>', 0)
            ->get();

        // Produits (Classe 7)
        $revenues = DB::table('chart_of_accounts')
            ->where('code', 'LIKE', '7%')
            ->leftJoin('accounting_entry_items', 'chart_of_accounts.id', '=', 'accounting_entry_items.chart_of_account_id')
            ->leftJoin('accounting_entries', function($join) use ($startDate, $endDate) {
                $join->on('accounting_entry_items.accounting_entry_id', '=', 'accounting_entries.id')
                     ->whereBetween('accounting_entries.entry_date', [$startDate, $endDate]);
            })
            ->select('chart_of_accounts.code', 'chart_of_accounts.label', 
                DB::raw('SUM(accounting_entry_items.credit - accounting_entry_items.debit) as amount'))
            ->groupBy('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.label')
            ->having('amount', '>', 0)
            ->get();

        $totalExpenses = $expenses->sum('amount');
        $totalRevenues = $revenues->sum('amount');
        $netResult     = $totalRevenues - $totalExpenses;

        return view('School::accounting.income_statement.index', compact(
            'expenses', 'revenues', 'totalExpenses', 'totalRevenues', 'netResult', 'startDate', 'endDate'
        ));
    }

    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-01-01'));
        $endDate   = $request->input('end_date', date('Y-m-d'));

        $expenses = DB::table('chart_of_accounts')
            ->where('code', 'LIKE', '6%')
            ->leftJoin('accounting_entry_items', 'chart_of_accounts.id', '=', 'accounting_entry_items.chart_of_account_id')
            ->leftJoin('accounting_entries', function($join) use ($startDate, $endDate) {
                $join->on('accounting_entry_items.accounting_entry_id', '=', 'accounting_entries.id')
                    ->whereBetween('accounting_entries.entry_date', [$startDate, $endDate]);
            })
            ->select('chart_of_accounts.code', 'chart_of_accounts.label', 
                DB::raw('SUM(accounting_entry_items.debit - accounting_entry_items.credit) as amount'))
            ->groupBy('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.label')
            ->having('amount', '>', 0)->get();

        $revenues = DB::table('chart_of_accounts')
            ->where('code', 'LIKE', '7%')
            ->leftJoin('accounting_entry_items', 'chart_of_accounts.id', '=', 'accounting_entry_items.chart_of_account_id')
            ->leftJoin('accounting_entries', function($join) use ($startDate, $endDate) {
                $join->on('accounting_entry_items.accounting_entry_id', '=', 'accounting_entries.id')
                    ->whereBetween('accounting_entries.entry_date', [$startDate, $endDate]);
            })
            ->select('chart_of_accounts.code', 'chart_of_accounts.label', 
                DB::raw('SUM(accounting_entry_items.credit - accounting_entry_items.debit) as amount'))
            ->groupBy('chart_of_accounts.id', 'chart_of_accounts.code', 'chart_of_accounts.label')
            ->having('amount', '>', 0)->get();

        $totalExpenses = $expenses->sum('amount');
        $totalRevenues = $revenues->sum('amount');
        $netResult     = $totalRevenues - $totalExpenses;

        $pdf = PDF::loadView('School::accounting.income_statement.pdf', compact('expenses', 'revenues', 'totalExpenses', 'totalRevenues', 'netResult', 'startDate', 'endDate'));
        return $pdf->download("Compte_de_Resultat_{$startDate}_{$endDate}.pdf");
    }
}