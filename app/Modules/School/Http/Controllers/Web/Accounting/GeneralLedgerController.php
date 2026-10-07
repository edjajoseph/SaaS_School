<?php

namespace App\Modules\School\Http\Controllers\Web\Accounting;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\ChartOfAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class GeneralLedgerController extends Controller
{
    /**
     * Affiche la Balance Générale des comptes comptables
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-01-01'));
        $endDate   = $request->input('end_date', date('Y-m-d'));

        $accounts = $this->getTrialBalanceData($startDate, $endDate);

        return view('school::accounting.general_ledger.index', compact('accounts', 'startDate', 'endDate'));
    }

    /**
     * Exporte la Balance Générale en PDF
     */
    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date', date('Y-01-01'));
        $endDate   = $request->input('end_date', date('Y-m-d'));

        $accounts = $this->getTrialBalanceData($startDate, $endDate);

        $pdf = Pdf::loadView('school::accounting.general_ledger.pdf', compact('accounts', 'startDate', 'endDate'));

        return $pdf->download("Balance_Generale_{$startDate}_{$endDate}.pdf");
    }

    /**
     * Récupère et calcule les soldes de la Balance Générale
     */
    private function getTrialBalanceData(string $startDate, string $endDate)
    {
        return ChartOfAccount::orderBy('code', 'asc')->get()->map(function ($account) use ($startDate, $endDate) {
            $movements = DB::table('accounting_entry_items')
                ->join('accounting_entries', 'accounting_entry_items.accounting_entry_id', '=', 'accounting_entries.id')
                ->where('accounting_entry_items.chart_of_account_id', $account->id)
                ->whereBetween('accounting_entries.entry_date', [$startDate, $endDate])
                ->selectRaw('SUM(debit) as total_debit, SUM(credit) as total_credit')
                ->first();

            $totalDebit  = $movements->total_debit ?? 0;
            $totalCredit = $movements->total_credit ?? 0;
            $balance     = $totalDebit - $totalCredit;

            return [
                'code'            => $account->code,
                'label'           => $account->label,
                'total_debit'     => $totalDebit,
                'total_credit'    => $totalCredit,
                'solde_debiteur'  => $balance > 0 ? $balance : 0,
                'solde_crediteur' => $balance < 0 ? abs($balance) : 0,
            ];
        })->filter(fn($row) => $row['total_debit'] > 0 || $row['total_credit'] > 0);
    }
}