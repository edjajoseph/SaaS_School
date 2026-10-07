<?php

namespace App\Modules\School\Http\Controllers\Web\Accounting;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\AccountingEntry;
use App\Modules\School\Models\AccountingJournal;
use App\Modules\School\Models\ChartOfAccount;
use App\Modules\School\Models\FiscalYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class AccountingEntryController extends Controller
{
    public function index()
    {
        $entries = AccountingEntry::with(['journal', 'fiscalYear', 'items.chartOfAccount'])
            ->orderBy('entry_date', 'desc')
            ->paginate(15);

        return view('school::accounting.entries.index', compact('entries'));
    }

    public function create()
    {
        $fiscalYears = FiscalYear::where('status', 'open')->get();
        $journals    = AccountingJournal::where('is_active', true)->orderBy('code')->get();
        $accounts    = ChartOfAccount::where('is_active', true)->orderBy('code')->get();

        return view('school::accounting.entries.create', compact('fiscalYears', 'journals', 'accounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fiscal_year_id' => 'required|exists:fiscal_years,id',
            'journal_id'     => 'required|exists:accounting_journals,id',
            'entry_number'   => 'required|string|max:255',
            'entry_date'     => 'required|date',
            'label'          => 'required|string|max:255',
            'items'          => 'required|array|min:2',
            'items.*.chart_of_account_id' => 'required|exists:chart_of_accounts,id',
            'items.*.debit'  => 'required|numeric|min:0',
            'items.*.credit' => 'required|numeric|min:0',
        ]);

        $totalDebit  = collect($request->items)->sum('debit');
        $totalCredit = collect($request->items)->sum('credit');

        if (round($totalDebit, 2) !== round($totalCredit, 2)) {
            return back()->withInput()->withErrors(['items' => 'L\'écriture comptable n\'est pas équilibrée. Total Débit (' . $totalDebit . ') != Total Crédit (' . $totalCredit . ')']);
        }

        DB::transaction(function () use ($request) {
            $entry = AccountingEntry::create([
                'fiscal_year_id' => $request->fiscal_year_id,
                'journal_id'     => $request->journal_id,
                'entry_number'   => $request->entry_number,
                'entry_date'     => $request->entry_date,
                'label'          => $request->label,
                'created_by_id'  => auth()->id(),
            ]);

            foreach ($request->items as $item) {
                $entry->items()->create([
                    'chart_of_account_id' => $item['chart_of_account_id'],
                    'label'               => $item['label'] ?? $request->label,
                    'debit'               => $item['debit'],
                    'credit'              => $item['credit'],
                ]);
            }
        });

        return redirect()->route('school.accounting.entries.index')
            ->with('success', 'Pièce comptable enregistrée avec succès.');
    }

    public function show(AccountingEntry $entry)
    {
        $entry->load(['journal', 'fiscalYear', 'items.chartOfAccount', 'createdBy']);
        return view('school::accounting.entries.show', compact('entry'));
    }

    public function destroy(AccountingEntry $entry)
    {
        $entry->delete();
        return redirect()->route('school.accounting.entries.index')
            ->with('success', 'Pièce comptable supprimée.');
    }

    public function pdf(AccountingEntry $entry)
    {
        $entry->load(['journal', 'fiscalYear', 'items.chartOfAccount', 'createdBy']);
        $pdf = PDF::loadView('school::accounting.entries.pdf', compact('entry'));

        return $pdf->download("Piece_Comptable_{$entry->entry_number}.pdf");
    }
}