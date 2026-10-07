<?php

namespace App\Modules\School\Http\Controllers\Web\Settings;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\Speciality;
use App\Modules\School\Models\School;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SpecialityController extends Controller
{
    public function index(): View
    {
        $specialities = Speciality::with('school')->get();
        return view('School::settings.specialities.index', compact('specialities'));
    }

    public function create(): View
    {
        $schools = School::all();
        return view('School::settings.specialities.create', compact('schools'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_id' => ['nullable', 'exists:schools,id'],
            'code'      => ['nullable', 'string', 'max:50'],
            'name'      => ['required', 'string', 'max:255'],
        ]);

        Speciality::create($validated);
        return redirect()->route('settings.rh.specialities.index')->with('success', 'Spécialité ajoutée avec succès.');
    }

    public function show(Speciality $speciality): View
    {
        $speciality->load('school');
        return view('School::settings.specialities.show', compact('speciality'));
    }

    public function edit(Speciality $speciality): View
    {
        $schools = School::all();
        return view('School::settings.specialities.edit', compact('speciality', 'schools'));
    }

    public function update(Request $request, Speciality $speciality): RedirectResponse
    {
        $validated = $request->validate([
            'school_id' => ['nullable', 'exists:schools,id'],
            'code'      => ['nullable', 'string', 'max:50'],
            'name'      => ['required', 'string', 'max:255'],
        ]);

        $speciality->update($validated);
        return redirect()->route('settings.rh.specialities.index')->with('success', 'Spécialité mise à jour avec succès.');
    }

    public function destroy(Speciality $speciality): RedirectResponse
    {
        $speciality->delete();
        return redirect()->route('settings.rh.specialities.index')->with('success', 'Spécialité supprimée avec succès.');
    }
}