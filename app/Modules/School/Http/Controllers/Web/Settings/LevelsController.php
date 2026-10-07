<?php

namespace App\Modules\School\Http\Controllers\Web\Settings;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\Cycle;
use App\Modules\School\Models\Level;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LevelsController extends Controller
{
    /**
     * Liste tous les niveaux (avec possibilité de filtrer par cycle ou statut).
     */
    public function index(Request $request): View
    {
        $query = Level::with('cycle');

        // Filtrer par cycle si renseigné
        if ($request->has('cycle_id') && $request->filled('cycle_id')) {
            $query->where('cycle_id', $request->input('cycle_id'));
        }

        // Filtrer par statut is_active si renseigné
        if ($request->has('is_active') && $request->input('is_active') !== null) {
            $query->where('is_active', filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN));
        }

        $levels = $query->orderBy('sequence_order')->get();

        return view('school::settings.levels.index', compact('levels'));
    }

    /**
     * Affiche le formulaire de création d'un niveau.
     */
    public function create(Request $request): View
    {
        $cycles = Cycle::all();

        return view('school::settings.levels.create', compact('cycles'));
    }

    /**
     * Enregistre un nouveau niveau.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cycle_id'       => ['required', 'exists:cycles,id'],
            'code'           => ['required', 'string', 'max:30', 'unique:levels,code'],
            'name'           => ['required', 'string', 'max:255'],
            'tuition_fee'    => ['nullable', 'numeric', 'min:0'], // 👈 Prise en compte du tarif
            'has_exam'       => ['sometimes', 'boolean'],
            'exam_name'      => ['nullable', 'required_if:has_exam,true,1', 'string', 'max:255'],
            'sequence_order' => ['sometimes', 'integer', 'min:1'],
            'is_active'      => ['sometimes', 'boolean'],
        ]);

        $validated['has_exam'] = $request->has('has_exam');
        $validated['is_active'] = $request->has('is_active');
        $validated['tuition_fee'] = $validated['tuition_fee'] ?? 0;

        Level::create($validated);

        return redirect()
             ->route('settings.academic.levels.index')
            ->with('success', 'Le niveau a été créé avec succès.');
    }

    /**
     * Affiche un niveau spécifique.
     */
    public function show(Level $level): View
    {
        $cycles = Cycle::all();

        return view('school::settings.levels.show', compact('level', 'cycles'));
    }

    /**
     * Affiche le formulaire d'édition d'un niveau.
     */
    public function edit(Level $level): View
    {
        $cycles = Cycle::all();

        return view('school::settings.levels.edit', compact('level', 'cycles'));
    }

    /**
     * Met à jour un niveau existant.
     */
    public function update(Request $request, Level $level): RedirectResponse
    {
        $validated = $request->validate([
            'cycle_id'       => ['sometimes', 'exists:cycles,id'],
            'code'           => ['sometimes', 'string', 'max:30', Rule::unique('levels', 'code')->ignore($level->id)],
            'name'           => ['sometimes', 'string', 'max:255'],
            'tuition_fee'    => ['nullable', 'numeric', 'min:0'], // 👈 Prise en compte du tarif
            'has_exam'       => ['sometimes', 'boolean'],
            'exam_name'      => ['nullable', 'required_if:has_exam,true,1', 'string', 'max:255'],
            'sequence_order' => ['sometimes', 'integer', 'min:1'],
            'is_active'      => ['sometimes', 'boolean'],
        ]);

        if ($request->has('has_exam')) {
            $validated['has_exam'] = $request->boolean('has_exam');
        }

        if ($request->has('is_active')) {
            $validated['is_active'] = $request->boolean('is_active');
        }

        $level->update($validated);

        return redirect()
             ->route('settings.academic.levels.index')
            ->with('success', 'Le niveau a été mis à jour avec succès.');
    }

    /**
     * Bascule rapidement l'état d'activation (Activer / Désactiver).
     */
    public function toggleActive(Level $level): RedirectResponse
    {
        $level->update([
            'is_active' => !$level->is_active,
        ]);

        $status = $level->is_active ? 'activé' : 'désactivé';

        return back()->with('success', "Le niveau {$level->name} a été {$status} avec succès.");
    }

    /**
     * Supprime un niveau.
     */
    public function destroy(Level $level): RedirectResponse
    {
        $level->delete();

        return redirect()
             ->route('settings.academic.levels.index')
            ->with('success', 'Le niveau a été supprimé avec succès.');
    }
}