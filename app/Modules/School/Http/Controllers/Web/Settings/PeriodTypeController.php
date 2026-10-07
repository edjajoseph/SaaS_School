<?php

namespace App\Modules\School\Http\Controllers\Web\Settings;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\PeriodType;
use App\Modules\School\Models\PeriodTypeItem;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class PeriodTypeController extends Controller
{
    /**
     * Liste des types de découpage
     */
    public function index(): View
    {
        $periodTypes = PeriodType::withCount('items')
            ->with(['items' => fn ($q) => $q->orderBy('sequence_order')])
            ->get();

        return view('School::settings.period_types.index', compact('periodTypes'));
    }

    /**
     * Formulaire de création d'un type de découpage
     */
    public function create(): View
    {
        return view('School::settings.period_types.create');
    }

    /**
     * Enregistrement d'un nouveau type de découpage et de ses éléments
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'code'                  => ['required', 'string', 'max:50', 'unique:period_types,code'],
            'description'           => ['nullable', 'string'],
            'items'                 => ['required', 'array', 'min:1'],
            'items.*.name'          => ['required', 'string', 'max:255'],
            'items.*.code'          => ['nullable', 'string', 'max:50'],
            'items.*.sequence_order' => ['required', 'integer', 'min:1'],
        ]);

        DB::transaction(function () use ($validated) {
            $periodType = PeriodType::create([
                'name'        => $validated['name'],
                'code'        => strtoupper($validated['code']),
                'description' => $validated['description'] ?? null,
            ]);

            foreach ($validated['items'] as $itemData) {
                $periodType->items()->create([
                    'name'           => $itemData['name'],
                    'code'           => isset($itemData['code']) ? strtoupper($itemData['code']) : null,
                    'sequence_order' => $itemData['sequence_order'],
                ]);
            }
        });

        return redirect() ->route('settings.academic.period-types.index')
                         ->with('success', 'Type de découpage créé avec succès.');
    }

    /**
     * Affichage d'un type de découpage spécifique
     */
    public function show(PeriodType $periodType): View
    {
        $periodType->load(['items' => fn ($q) => $q->orderBy('sequence_order')]);

        return view('School::settings.period_types.show', compact('periodType'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(PeriodType $periodType): View
    {
        $periodType->load(['items' => fn ($q) => $q->orderBy('sequence_order')]);

        return view('School::settings.period_types.edit', compact('periodType'));
    }

    /**
     * Mise à jour d'un type de découpage
     */
    public function update(Request $request, PeriodType $periodType): RedirectResponse
    {
        $validated = $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'code'                  => ['required', 'string', 'max:50', Rule::unique('period_types', 'code')->ignore($periodType->id)],
            'description'           => ['nullable', 'string'],
            'items'                 => ['required', 'array', 'min:1'],
            'items.*.id'            => ['nullable', 'exists:period_type_items,id'],
            'items.*.name'          => ['required', 'string', 'max:255'],
            'items.*.code'          => ['nullable', 'string', 'max:50'],
            'items.*.sequence_order' => ['required', 'integer', 'min:1'],
        ]);

        DB::transaction(function () use ($validated, $periodType) {
            $periodType->update([
                'name'        => $validated['name'],
                'code'        => strtoupper($validated['code']),
                'description' => $validated['description'] ?? null,
            ]);

            $submittedItemIds = collect($validated['items'])->pluck('id')->filter()->toArray();

            // Supprimer les sous-éléments retirés du formulaire
            $periodType->items()->whereNotIn('id', $submittedItemIds)->delete();

            // Mettre à jour ou créer les sous-éléments
            foreach ($validated['items'] as $itemData) {
                $periodType->items()->updateOrCreate(
                    ['id' => $itemData['id'] ?? null],
                    [
                        'name'           => $itemData['name'],
                        'code'           => isset($itemData['code']) ? strtoupper($itemData['code']) : null,
                        'sequence_order' => $itemData['sequence_order'],
                    ]
                );
            }
        });

        return redirect() ->route('settings.academic.period-types.index')
                         ->with('success', 'Type de découpage mis à jour avec succès.');
    }

    /**
     * Suppression d'un type de découpage
     */
    public function destroy(PeriodType $periodType): RedirectResponse
    {
        // Empêcher la suppression si des périodes académiques y sont rattachées
        $hassettingsPeriods = PeriodTypeItem::where('period_type_id', $periodType->id)
            ->whereHas('settingsPeriods')
            ->exists();

        if ($hassettingsPeriods) {
            return redirect() ->route('settings.academic.period-types.index')
                             ->with('error', 'Impossible de supprimer ce type de découpage car il est actuellement utilisé dans des planifications d\'années académiques.');
        }

        $periodType->delete();

        return redirect() ->route('settings.academic.period-types.index')
                         ->with('success', 'Type de découpage supprimé avec succès.');
    }
}