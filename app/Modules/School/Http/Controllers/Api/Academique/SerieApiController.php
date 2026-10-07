<?php

namespace App\Modules\School\Http\Controllers\Api\Academique;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\Serie;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SerieApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Serie::withCount('classes');

        if ($request->has('is_active')) {
            $query->where('is_active', filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN));
        }

        return response()->json([
            'success' => true,
            'data'    => $query->orderBy('code')->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code'        => ['required', 'string', 'max:50', 'unique:series,code'],
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['sometimes', 'boolean'],
        ]);

        $serie = Serie::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Série créée avec succès.',
            'data'    => $serie,
        ], 201);
    }

    public function show(Serie $serie): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $serie->load(['classes.school', 'classes.level']),
        ]);
    }

    public function update(Request $request, Serie $serie): JsonResponse
    {
        $validated = $request->validate([
            'code'        => ['sometimes', 'string', 'max:50', Rule::unique('series', 'code')->ignore($serie->id)],
            'name'        => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active'   => ['sometimes', 'boolean'],
        ]);

        $serie->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Série mise à jour avec succès.',
            'data'    => $serie,
        ]);
    }

    public function toggleActive(Serie $serie): JsonResponse
    {
        $serie->update(['is_active' => !$serie->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Statut de la série mis à jour.',
            'data'    => ['id' => $serie->id, 'is_active' => $serie->is_active],
        ]);
    }

    public function destroy(Serie $serie): JsonResponse
    {
        $serie->delete();

        return response()->json([
            'success' => true,
            'message' => 'Série supprimée avec succès.',
        ]);
    }
}