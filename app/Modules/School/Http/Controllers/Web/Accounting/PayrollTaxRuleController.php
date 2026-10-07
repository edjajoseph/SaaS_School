<?php

namespace App\Modules\School\Http\Controllers\Web\Accounting;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\PayrollTaxRule;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class PayrollTaxRuleController extends Controller
{
    /**
     * Enregistre une nouvelle règle de cotisation/impôt
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_id'          => 'required|exists:schools,id',
            'code'               => 'required|string|max:50',
            'name'               => 'required|string|max:150',
            'category'           => 'required|in:social_salarial,social_patronal,tax_salarial,tax_patronal,mutual_salarial',
            'calculation_type'   => 'required|in:percentage,fixed_amount',
            'rate'               => 'nullable|numeric|min:0|max:100', // Saisi en % (ex: 6.3)
            'fixed_amount'       => 'nullable|numeric|min:0',
            'ceiling'            => 'nullable|numeric|min:0',
            'min_base'           => 'nullable|numeric|min:0',
            'is_active'          => 'nullable|boolean',
            'applies_to_vacants' => 'nullable|boolean',
        ]);

        // Conversion du pourcentage en décimal pour la BD (ex: 6.3% -> 0.063)
        $validated['rate'] = ($validated['calculation_type'] === 'percentage' && !empty($validated['rate']))
            ? ((float) $validated['rate'] / 100)
            : 0;

        $validated['fixed_amount'] = ($validated['calculation_type'] === 'fixed_amount')
            ? ($validated['fixed_amount'] ?? 0)
            : 0;

        $validated['is_active'] = $request->has('is_active');
        $validated['applies_to_vacants'] = $request->has('applies_to_vacants');

        PayrollTaxRule::create($validated);

        return redirect()->back()->with('success', 'Règle de cotisation ajoutée avec succès.');
    }

    /**
     * Met à jour une règle existante
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $rule = PayrollTaxRule::findOrFail($id);

        $validated = $request->validate([
            'code'               => 'required|string|max:50',
            'name'               => 'required|string|max:150',
            'category'           => 'required|in:social_salarial,social_patronal,tax_salarial,tax_patronal,mutual_salarial',
            'calculation_type'   => 'required|in:percentage,fixed_amount',
            'rate'               => 'nullable|numeric|min:0|max:100',
            'fixed_amount'       => 'nullable|numeric|min:0',
            'ceiling'            => 'nullable|numeric|min:0',
            'min_base'           => 'nullable|numeric|min:0',
            'is_active'          => 'nullable|boolean',
            'applies_to_vacants' => 'nullable|boolean',
        ]);

        $validated['rate'] = ($validated['calculation_type'] === 'percentage' && !empty($validated['rate']))
            ? ((float) $validated['rate'] / 100)
            : 0;

        $validated['fixed_amount'] = ($validated['calculation_type'] === 'fixed_amount')
            ? ($validated['fixed_amount'] ?? 0)
            : 0;

        $validated['is_active'] = $request->has('is_active');
        $validated['applies_to_vacants'] = $request->has('applies_to_vacants');

        $rule->update($validated);

        return redirect()->back()->with('success', 'Règle de cotisation mise à jour.');
    }

    /**
     * Supprime une règle (SoftDelete)
     */
    public function destroy(int $id): RedirectResponse
    {
        $rule = PayrollTaxRule::findOrFail($id);
        $rule->delete();

        return redirect()->back()->with('success', 'Règle de cotisation supprimée.');
    }
}