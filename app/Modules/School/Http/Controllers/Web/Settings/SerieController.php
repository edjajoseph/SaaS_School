<?php

namespace App\Modules\School\Http\Controllers\Web\Settings;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\Serie;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SerieController extends Controller
{
    /**
     * Liste les séries.
     */
    public function index(Request $request): View
    {
        $query = Serie::query();

        if ($request->has('is_active') && $request->input('is_active') !== null) {
            $query->where('is_active', filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $series = $query->orderBy('code')->paginate(15)->withQueryString();

        return view('School::settings.series.index', compact('series'));
    }

    /**
     * Formulaire de création.
     */
    public function create(): View
    {
        return view('School::settings.series.create');
    }

    /**
     * Enregistre une nouvelle série.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code'        => ['required', 'string', 'max:50', 'unique:series,code'],
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $serie = Serie::create($validated);

        return redirect() ->route('settings.academic.series.index')
                         ->with('success', 'Série créée avec succès.');
    }

    /**
     * Affiche une série avec ses classes.
     */
    public function show(Serie $serie): View
    {
        // Retirer le chargement de classes.school et classes.level
        return view('School::settings.series.show', compact('serie'));
    }

    /**
     * Formulaire d'édition.
     */
    public function edit(Serie $serie): View
    {
        return view('School::settings.series.edit', compact('serie'));
    }

    /**
     * Met à jour une série.
     */
    public function update(Request $request, Serie $serie): RedirectResponse
    {
        $validated = $request->validate([
            'code'        => ['required', 'string', 'max:50', Rule::unique('series', 'code')->ignore($serie->id)],
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['sometimes', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $serie->update($validated);

        return redirect() ->route('settings.academic.series.index', $serie)
                         ->with('success', 'Série mise à jour avec succès.');
    }

    /**
     * Active ou désactive la série.
     */
    public function toggleActive(Serie $serie): RedirectResponse
    {
        $serie->update([
            'is_active' => !$serie->is_active,
        ]);

        return redirect()->back()->with('success', 'Statut de la série mis à jour.');
    }

    /**
     * Supprime une série.
     */
    public function destroy(Serie $serie): RedirectResponse
    {
        $serie->delete();

        return redirect() ->route('settings.academic.series.index')
                         ->with('success', 'Série supprimée avec succès.');
    }
}