<?php

namespace App\Modules\School\Http\Controllers\Web\Academique;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\TeachingUnit;
use App\Modules\School\Models\Level;
use App\Modules\School\Models\School;
use App\Modules\School\Models\Serie;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class TeachingUnitController extends Controller
{
    /**
     * Liste des Unités d'Enseignement avec filtres et recherche.
     */
    public function index(Request $request): View
    {
        $query = TeachingUnit::with(['school', 'level'])
            ->withCount('schoolsubjects');

        // Filtre par école
        if ($request->filled('school_id')) {
            $query->where('school_id', $request->school_id);
        }

        // Filtre par niveau d'étude
        if ($request->filled('level_id')) {
            $query->where('level_id', $request->level_id);
        }

        // Filtre par type (Fondamentale, Transversale, Optionnelle, etc.)
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Recherche par nom ou code
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $teachingUnits = $query->latest()->paginate(15)->withQueryString();
        $schools = School::where('is_active', true)->get();
        $levels = Level::all();

        return view('School::academique.teaching_units.index', compact('teachingUnits', 'schools', 'levels'));
    }

    /**
     * Formulaire de création d'une UE.
     */
    public function create(): View
    {
        $schools = School::where('is_active', true)->get();
        // Utilisation de select('levels.*') pour éviter la collision d'ID avec la table pivot
        $levels = Level::join('level_school', 'level_school.level_id', '=', 'levels.id')
            ->select('levels.*')
            ->distinct()
            ->get();

        // Utilisation de select('series.*') pour éviter la collision d'ID avec la table pivot
        $series = Serie::join('school_series', 'school_series.serie_id', '=', 'series.id')
            ->select('series.*')
            ->distinct()
            ->get();

        return view('School::academique.teaching_units.create', compact('schools', 'levels', 'series'));
    }

    /**
     * Enregistrement d'une nouvelle UE.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'school_id'   => ['required', 'exists:schools,id'],
            'level_id'    => ['required', 'exists:levels,id'],
            'serie_id'    => ['required', 'exists:series,id'],
            'name'        => ['required', 'string', 'max:255'],
            'code'        => ['required', 'string', 'max:50', Rule::unique('teaching_units')->where('school_id', $request->school_id)],
            'type'        => ['nullable', 'string', 'max:100'],
            'credits'     => ['required', 'integer', 'min:1'],
            'coefficient' => ['required', 'numeric', 'min:0'],
            'is_active'   => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        TeachingUnit::create($validated);

        return redirect()
        ->route('academic.teaching-units.index')
            ->with('success', __('Unité d\'Enseignement créée avec succès.'));
    }

    /**
     * Affichage d'une UE avec ses ECUE / Matières associées.
     */
    public function show(TeachingUnit $teachingUnit): View
    {
        $teachingUnit->load(['school', 'level', 'schoolsubjects']);

        return view('School::academique.teaching_units.show', compact('teachingUnit'));
    }

    /**
     * Formulaire de modification d'une UE.
     */
    public function edit(TeachingUnit $teachingUnit): View
    {
        $schools = School::where('is_active', true)->get();
        // Utilisation de select('levels.*') pour éviter la collision d'ID avec la table pivot
        $levels = Level::join('level_school', 'level_school.level_id', '=', 'levels.id')
            ->select('levels.*')
            ->distinct()
            ->get();

        // Utilisation de select('series.*') pour éviter la collision d'ID avec la table pivot
        $series = Serie::join('school_series', 'school_series.serie_id', '=', 'series.id')
            ->select('series.*')
            ->distinct()
            ->get();

        return view('School::academique.teaching_units.edit', compact('teachingUnit', 'schools', 'levels', 'series'));
    }

    /**
     * Mise à jour de l'UE.
     */
    public function update(Request $request, TeachingUnit $teachingUnit): RedirectResponse
    {
        $validated = $request->validate([
            'school_id'   => ['required', 'exists:schools,id'],
            'level_id'    => ['required', 'exists:levels,id'],
            'serie_id'    => ['required', 'exists:series,id'],
            'name'        => ['required', 'string', 'max:255'],
            'code'        => [
                'required', 
                'string', 
                'max:50', 
                Rule::unique('teaching_units')
                    ->where('school_id', $request->school_id)
                    ->ignore($teachingUnit->id)
            ],
            'type'        => ['nullable', 'string', 'max:100'],
            'credits'     => ['required', 'integer', 'min:1'],
            'coefficient' => ['required', 'numeric', 'min:0'],
            'is_active'   => ['boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        $teachingUnit->update($validated);

        return redirect()
            ->route('academic.teaching-units.index')
            ->with('success', __('Unité d\'Enseignement mise à jour avec succès.'));
    }

    /**
     * Suppression d'une UE.
     */
    public function destroy(TeachingUnit $teachingUnit): RedirectResponse
    {
        // Empêcher la suppression si des matières (ECUE) y sont déjà rattachées
        if ($teachingUnit->schoolsubjects()->exists()) {
            return back()->with('error', __('Impossible de supprimer cette UE car elle contient des matières (ECUE).'));
        }

        $teachingUnit->delete();

        return redirect()
        ->route('academic.teaching-units.index')
            ->with('success', __('Unité d\'Enseignement supprimée avec succès.'));
    }

    /**
     * Bascule le statut d'activation (Actif / Inactif).
     */
    public function toggleActive(TeachingUnit $teachingUnit): RedirectResponse
    {
        $teachingUnit->update([
            'is_active' => !$teachingUnit->is_active,
        ]);

        return redirect()->back()->with('success', 'Statut de l\'UE mis à jour.');
    }
}