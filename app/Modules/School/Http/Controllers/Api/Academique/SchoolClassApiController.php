<?php

namespace App\Modules\School\Http\Controllers\Api\Academique;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\SchoolClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SchoolClassApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = SchoolClass::with(['school', 'level']);

        if ($request->filled('school_id')) {
            $query->where('school_id', $request->input('school_id'));
        }

        if ($request->filled('level_id')) {
            $query->where('level_id', $request->input('level_id'));
        }

        if ($request->has('is_active')) {
            $query->where('is_active', filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN));
        }

        return response()->json([
            'success' => true,
            'data'    => $query->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'school_id'   => ['required', 'exists:schools,id'],
            'level_id'    => ['required', 'exists:levels,id'],
            'name'        => [
                'required', 
                'string', 
                'max:255',
                Rule::unique('school_classes')->where(fn ($q) => $q->where('school_id', $request->school_id)),
            ],
            'code'        => ['nullable', 'string', 'max:50'],
            'capacity'    => ['nullable', 'integer', 'min:1'],
            'tuition_fee' => ['nullable', 'numeric', 'min:0'],
            'is_active'   => ['sometimes', 'boolean'],
        ]);

        $schoolClass = SchoolClass::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Classe créée avec succès.',
            'data'    => $schoolClass->load(['school', 'level']),
        ], 201);
    }

    public function show(SchoolClass $schoolClass): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $schoolClass->load(['school', 'level']),
        ]);
    }

    public function update(Request $request, SchoolClass $schoolClass): JsonResponse
    {
        $validated = $request->validate([
            'school_id'   => ['sometimes', 'exists:schools,id'],
            'level_id'    => ['sometimes', 'exists:levels,id'],
            'name'        => [
                'sometimes', 
                'string', 
                'max:255',
                Rule::unique('school_classes')
                    ->where(fn ($q) => $q->where('school_id', $request->input('school_id', $schoolClass->school_id)))
                    ->ignore($schoolClass->id),
            ],
            'code'        => ['nullable', 'string', 'max:50'],
            'capacity'    => ['nullable', 'integer', 'min:1'],
            'tuition_fee' => ['nullable', 'numeric', 'min:0'],
            'is_active'   => ['sometimes', 'boolean'],
        ]);

        $schoolClass->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Classe mise à jour avec succès.',
            'data'    => $schoolClass->load(['school', 'level']),
        ]);
    }

    public function toggleActive(SchoolClass $schoolClass): JsonResponse
    {
        $schoolClass->update(['is_active' => !$schoolClass->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Statut de la classe mis à jour.',
            'data'    => ['id' => $schoolClass->id, 'is_active' => $schoolClass->is_active],
        ]);
    }

    public function destroy(SchoolClass $schoolClass): JsonResponse
    {
        $schoolClass->delete();

        return response()->json([
            'success' => true,
            'message' => 'Classe supprimée avec succès.',
        ]);
    }
}