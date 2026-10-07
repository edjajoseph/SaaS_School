<?php

namespace App\Modules\School\Http\Controllers\Web\Accounting;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\AccountingJournal;
use App\Modules\School\Models\ChartOfAccount;
use Illuminate\Http\Request;

class JournalController extends Controller
{
    public function index()
    {
        $journals = AccountingJournal::with('defaultAccount')->orderBy('code', 'asc')->get();
        return view('school::accounting.journals.index', compact('journals'));
    }

    public function create()
    {
        $accounts = ChartOfAccount::where('is_active', true)->orderBy('code', 'asc')->get();
        return view('school::accounting.journals.create', compact('accounts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'               => 'required|string|max:10|unique:accounting_journals,code',
            'name'               => 'required|string|max:255',
            'type'               => 'required|in:bank,cash,sales,purchase,general',
            'default_account_id' => 'nullable|exists:chart_of_accounts,id',
        ]);

        AccountingJournal::create($validated);

        return redirect()->route('school.accounting.journals.index')
            ->with('success', 'Journal comptable créé avec succès.');
    }

    public function edit(AccountingJournal $journal)
    {
        $accounts = ChartOfAccount::where('is_active', true)->orderBy('code', 'asc')->get();
        return view('school::accounting.journals.edit', compact('journal', 'accounts'));
    }

    public function update(Request $request, AccountingJournal $journal)
    {
        $validated = $request->validate([
            'code'               => 'required|string|max:10|unique:accounting_journals,code,' . $journal->id,
            'name'               => 'required|string|max:255',
            'type'               => 'required|in:bank,cash,sales,purchase,general',
            'default_account_id' => 'nullable|exists:chart_of_accounts,id',
        ]);

        $journal->update($validated);

        return redirect()->route('school.accounting.journals.index')
            ->with('success', 'Journal comptable mis à jour.');
    }

    public function destroy(AccountingJournal $journal)
    {
        if ($journal->entries()->exists()) {
            return redirect()->route('school.accounting.journals.index')
                ->with('error', 'Impossible de supprimer un journal contenant déjà des écritures.');
        }

        $journal->delete();

        return redirect()->route('school.accounting.journals.index')
            ->with('success', 'Journal supprimé.');
    }
}