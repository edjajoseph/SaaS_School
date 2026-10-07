<?php

namespace App\Modules\School\Http\Controllers\Web\Settings;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\Degree;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DegreeController extends Controller
{
    public function index(): View
    {
        $degrees = Degree::orderBy('level', 'asc')->get();
        return view('School::settings.degrees.index', compact('degrees'));
    }

    public function create(): View
    {
        return view('School::settings.degrees.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code'       => ['nullable', 'string', 'max:50'],
            'name'       => ['required', 'string', 'max:255'],
            'level'      => ['nullable', 'string', ''],
            'is_active'      => ['sometimes', 'boolean'],
        ]);

        Degree::create($validated);
        return redirect() ->route('settings.academic.degrees.index')->with('success', 'Diplôme ajouté avec succès.');
    }

    public function show(Degree $degree): View
    {
        return view('School::settings.degrees.show', compact('degree'));
    }

    public function edit(Degree $degree): View
    {
        return view('School::settings.degrees.edit', compact('degree'));
    }

    public function update(Request $request, Degree $degree): RedirectResponse
    {
        $validated = $request->validate([
            'code'       => ['nullable', 'string', 'max:50'],
            'name'       => ['required', 'string', 'max:255'],
            'level'      => ['nullable', 'string', ''],
            'is_active'  => ['sometimes', 'boolean'],
        ]);

        $degree->update($validated);
        return redirect() ->route('settings.academic.degrees.index')->with('success', 'Diplôme mis à jour avec succès.');
    }

    public function destroy(Degree $degree): RedirectResponse
    {
        $degree->delete();
        return redirect() ->route('settings.academic.degrees.index')->with('success', 'Diplôme supprimé avec succès.');
    }
}