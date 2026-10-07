<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Solution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SolutionController extends Controller
{
    /**
     * Affiche la liste des solutions SaaS.
     */
    public function index(): View
    {
        $solutions = Solution::withCount(['tenants', 'plans'])
            ->latest()
            ->paginate(10);

        return view('central.solutions.index', compact('solutions'));
    }

    /**
     * Affiche le formulaire de création d'une nouvelle solution.
     */
    public function create(): View
    {
        return view('central.solutions.create');
    }

    /**
     * Enregistre une nouvelle solution en base de données.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom'         => 'required|string|max:255',
            'code'        => 'required|string|max:50|unique:solutions,code|alpha_dash',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Solution::create($validated);

        return redirect()
            ->route('solutions.index')
            ->with('success', 'La solution SaaS a été créée avec succès.');
    }

    /**
     * Affiche les détails d'une solution spécifique.
     */
    public function show(Solution $solution): View
    {
        $solution->load(['plans', 'tenants' => function ($query) {
            $query->latest()->limit(10);
        }]);

        return view('central.solutions.show', compact('solution'));
    }

    /**
     * Affiche le formulaire d'édition d'une solution.
     */
    public function edit(Solution $solution): View
    {
        return view('central.solutions.edit', compact('solution'));
    }

    /**
     * Met à jour les informations de la solution.
     */
    public function update(Request $request, Solution $solution): RedirectResponse
    {
        $validated = $request->validate([
            'nom'         => 'required|string|max:255',
            'code'        => 'required|string|max:50|alpha_dash|unique:solutions,code,' . $solution->id,
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $solution->update($validated);

        return redirect()
            ->route('solutions.index')
            ->with('success', 'La solution a été mise à jour avec succès.');
    }

    /**
     * Supprime une solution (si aucun tenant ne lui est rattaché).
     */
    public function destroy(Solution $solution): RedirectResponse
    {
        if ($solution->tenants()->exists()) {
            return redirect()
                ->route('solutions.index')
                ->with('error', 'Impossible de supprimer cette solution : des clients (tenants) y sont actuellement associés.');
        }

        $solution->delete();

        return redirect()
            ->route('solutions.index')
            ->with('success', 'La solution a été supprimée avec succès.');
    }
}