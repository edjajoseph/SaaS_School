<?php

namespace App\Modules\School\Http\Controllers\Api\Academique;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\Level;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LevelsController extends Controller
{
    /**
     * Liste tous les niveaux (avec possibilité de filtrer par cycle ou statut).
     */
    public function index(Request $request): JsonResponse
    {
        $query = Level::with('cycle');

        // Filtrer par cycle si renseigné
        if ($request->has('cycle_id')) {
            $query->where('cycle_id', $request->input('cycle_id'));
        }

        // Filtrer par statut is_active si renseigné
        if ($request->has('is_active')) {
            $query->where('is_active', filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN));
        }

        $levels = $query->orderBy('sequence_order')->get();

        return response()->json([
            'success' => true,
            'data'    => $levels,
        ]);
    }

    /**
     * Enregistre un nouveau niveau.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'cycle_id'       => ['required', 'exists:cycles,id'],
            'code'           => ['required', 'string', 'max:30', 'unique:levels,code'],
            'name'           => ['required', 'string', 'max:255'],
            'has_exam'       => ['sometimes', 'boolean'],
            'exam_name'      => ['nullable', 'required_if:has_exam,true', 'string', 'max:255'],
            'sequence_order' => ['sometimes', 'integer', 'min:1'],
            'is_active'      => ['sometimes', 'boolean'],
        ]);

        $level = Level::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Niveau créé avec succès.',
            'data'    => $level->load('cycle'),
        ], 210);
    }

    /**
     * Affiche un niveau spécifique.
     */
    public function show(Level $level): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $level->load('cycle'),
        ]);
    }

    /**
     * Met à jour un niveau existant.
     */
    public function update(Request $request, Level $level): JsonResponse
    {
        $validated = $request->validate([
            'cycle_id'       => ['sometimes', 'exists:cycles,id'],
            'code'           => ['sometimes', 'string', 'max:30', 'unique:levels,code,' . $level->id],
            'name'           => ['sometimes', 'string', 'max:255'],
            'has_exam'       => ['sometimes', 'boolean'],
            'exam_name'      => ['nullable', 'required_if:has_exam,true', 'string', 'max:255'],
            'sequence_order' => ['sometimes', 'integer', 'min:1'],
            'is_active'      => ['sometimes', 'boolean'],
        ]);

        $level->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Niveau mis à jour avec succès.',
            'data'    => $level->load('cycle'),
        ]);
    }

    /**
     * Bascule rapidement l'état d'activation (Activer / Désactiver).
     */
    public function toggleActive(Level $level): JsonResponse
    {
        $level->update([
            'is_active' => !$level->is_active,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Statut du niveau mis à jour.',
            'data'    => [
                'id'        => $level->id,
                'is_active' => $level->is_active,
            ],
        ]);
    }

    /**
     * Supprime un niveau.
     */
    public function destroy(Level $level): JsonResponse
    {
        $level->delete();

        return response()->json([
            'success' => true,
            'message' => 'Niveau supprimé avec succès.',
        ]);
    }
}