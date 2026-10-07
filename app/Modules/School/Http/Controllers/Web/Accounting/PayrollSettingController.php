<?php

namespace App\Modules\School\Http\Controllers\Web\Accounting;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\PayrollSetting;
use App\Modules\School\Models\PayrollTaxRule;
use App\Modules\School\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PayrollSettingController extends Controller
{
    /**
     * Page de paramétrage général et liste des règles de cotisations
     */
    public function index(Request $request): View
    {
        $schoolId = $request->input('school_id', auth()->user()->school_id ?? 1);

        $setting = PayrollSetting::firstOrCreate(
            ['school_id' => $schoolId],
            [
                'country_id'             => auth()->user()->school->country_id ?? null,
                'apply_taxes_to_vacants' => false,
            ]
        );

        $taxRules = PayrollTaxRule::where('school_id', $schoolId)
            ->orderBy('category', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        $countries = Country::orderBy('name', 'asc')->get();

        return view('school::payroll.settings.index', compact('setting', 'taxRules', 'countries', 'schoolId'));
    }

    /**
     * Mise à jour des paramètres généraux
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $setting = PayrollSetting::findOrFail($id);

        $validated = $request->validate([
            'country_id'             => 'nullable|exists:countries,id',
            'apply_taxes_to_vacants' => 'nullable|boolean',
        ]);

        $validated['apply_taxes_to_vacants'] = $request->has('apply_taxes_to_vacants');

        $setting->update($validated);

        return redirect()->back()->with('success', 'Paramètres généraux mis à jour avec succès.');
    }
}