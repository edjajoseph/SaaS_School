<?php

namespace App\Modules\School\Http\Controllers\Web\Settings;

use App\Http\Controllers\Controller;
use App\Modules\School\Http\Requests\StoreCycleRequest;
use App\Modules\School\Models\Cycle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CycleController extends Controller
{
    /**
     * Affiche la liste des cycles.
     */
    public function index(Request $request): View
    {
        $cycles = Cycle::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('code', 'like', '%' . $request->search . '%');
            })
            ->orderBy('sequence_order')
            ->paginate(15);

        return view('school::settings.cycles.index', compact('cycles'));
    }

    /**
     * Affiche le formulaire de création d'un cycle.
     */
    public function create(Request $request): View
    {
        
        return view('school::settings.cycles.create');
    }

    /**
     * Enregistre un nouveau cycle dans la base de données.
     */
    public function store(StoreCycleRequest $request): RedirectResponse
    {
        Cycle::create($request->validated());

        return redirect()
             ->route('settings.academic.academic.cycles.index')
            ->with('success', 'Le cycle a été créé avec succès.');
    }

    /**
     * Affiche les détails d'un cycle spécifique avec ses niveaux.
     */
    public function show(Cycle $cycle): View
    {
        $cycle->load('levels');

        return view('school::settings.cycles.show', compact('cycle'));
    }

    /**
     * Affiche le formulaire d'édition d'un cycle.
     */
    public function edit(Cycle $cycle): View
    {
        return view('school::settings.cycles.edit', compact('cycle'));
    }

    /**
     * Met à jour le cycle spécifié.
     */
    public function update(StoreCycleRequest $request, Cycle $cycle): RedirectResponse
    {
        $cycle->update($request->validated());

        return redirect()
             ->route('settings.academic.cycles.index')
            ->with('success', 'Le cycle a été mis à jour avec succès.');
    }

    public function toggleStatus($id)
    {
        $cycle = Cycle::findOrFail($id);

        // Inversion de l'état (assure-toi d'avoir la colonne 'is_active' ou adapte selon ta base)
        $cycle->is_active = !$cycle->is_active;
        $cycle->save();

        $statut = $cycle->is_active ? 'activé' : 'désactivé';

        return redirect()->back()->with('success', "Le cycle a été {$statut} avec succès.");
    }


    /**
     * Supprime le cycle spécifié.
     */
    public function destroy(Cycle $cycle): RedirectResponse
    {
        if ($cycle->levels()->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Impossible de supprimer ce cycle car des niveaux y sont associés.');
        }

        $cycle->delete();

        return redirect()
             ->route('settings.academic.cycles.index')
            ->with('success', 'Le cycle a été supprimé avec succès.');
    }
}