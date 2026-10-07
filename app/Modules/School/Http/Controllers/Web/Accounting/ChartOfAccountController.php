<?php

namespace App\Modules\School\Http\Controllers\Web\Accounting;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\ChartOfAccount;
use Illuminate\Http\Request;

class ChartOfAccountController extends Controller
{
    public function index(Request $request)
    {
        $query = ChartOfAccount::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('label', 'like', "%{$search}%");
            });
        }

        $accounts = $query->orderBy('code', 'asc')->paginate(15)->withQueryString();
        return view('school::accounting.chart_of_accounts.index', compact('accounts'));
    }

    public function create()
    {
        return view('school::accounting.chart_of_accounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'      => 'required|string|max:20|unique:chart_of_accounts,code',
            'label'     => 'required|string|max:255',
            'type'      => 'required|in:asset,liability,equity,revenue,expense',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        ChartOfAccount::create($validated);

        return redirect()->route('school.accounting.chart-of-accounts.index')
            ->with('success', 'Compte ajouté au plan comptable.');
    }

    public function edit(ChartOfAccount $chartOfAccount)
    {
        return view('school::accounting.chart_of_accounts.edit', compact('chartOfAccount'));
    }

    public function update(Request $request, ChartOfAccount $chartOfAccount)
    {
        $validated = $request->validate([
            'code'      => 'required|string|max:20|unique:chart_of_accounts,code,' . $chartOfAccount->id,
            'label'     => 'required|string|max:255',
            'type'      => 'required|in:asset,liability,equity,revenue,expense',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $chartOfAccount->update($validated);

        return redirect()->route('school.accounting.chart-of-accounts.index')
            ->with('success', 'Compte mis à jour.');
    }

    public function destroy(ChartOfAccount $chartOfAccount)
    {
        $chartOfAccount->delete();
        return redirect()->route('school.accounting.chart-of-accounts.index')
            ->with('success', 'Compte supprimé.');
    }
}