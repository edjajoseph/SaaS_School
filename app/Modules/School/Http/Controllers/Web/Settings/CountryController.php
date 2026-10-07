<?php

namespace App\Modules\School\Http\Controllers\Web\Settings;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\Country;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CountryController extends Controller
{
    public function index(): View
    {
        $countries = Country::orderBy('name')->get();
        return view('School::settings.countries.index', compact('countries'));
    }

    public function create(): View
    {
        return view('School::settings.countries.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'nationality' => ['required', 'string', 'max:255'],
            'iso_code_2'  => ['required', 'string', 'size:2', 'unique:countries,iso_code_2'],
            'iso_code_3'  => ['nullable', 'string', 'size:3', 'unique:countries,iso_code_3'],
            'phone_code'  => ['nullable', 'string', 'max:10'],
            'is_active'   => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');
        Country::create($validated);

        return redirect()->route('settings.local.countries.index')->with('success', 'Pays ajouté avec succès.');
    }

    public function show(Country $country): View
    {
        return view('School::settings.countries.show', compact('country'));
    }

    public function edit(Country $country): View
    {
        return view('School::settings.countries.edit', compact('country'));
    }

    public function update(Request $request, Country $country): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'nationality' => ['required', 'string', 'max:255'],
            'iso_code_2'  => ['required', 'string', 'size:2', 'unique:countries,iso_code_2,' . $country->id],
            'iso_code_3'  => ['nullable', 'string', 'size:3', 'unique:countries,iso_code_3,' . $country->id],
            'phone_code'  => ['nullable', 'string', 'max:10'],
            'is_active'   => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');
        $country->update($validated);

        return redirect()->route('settings.local.countries.index')->with('success', 'Pays mis à jour avec succès.');
    }

    public function destroy(Country $country): RedirectResponse
    {
        $country->delete();
        return redirect()->route('settings.local.countries.index')->with('success', 'Pays supprimé avec succès.');
    }
}