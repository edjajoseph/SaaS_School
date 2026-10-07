<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SchoolSubject;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SchoolSubjectController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = SchoolSubject::with(['subject', 'teachingUnit']);

        if ($request->has('school_id')) {
            $query->where('school_id', $request->query('school_id'));
        }

        return response()->json($query->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'subject_id' => 'required|exists:subjects,id',
            'teaching_unit_id' => 'nullable|exists:teaching_units,id',
            'custom_name' => 'nullable|string|max:255',
            'code' => 'nullable|string|max:50',
            'color_code' => 'nullable|string|max:7',
            'credits' => 'numeric|min:0',
            'coefficient' => 'numeric|min:0',
            'hours_cm' => 'integer|min:0',
            'hours_td' => 'integer|min:0',
            'hours_tp' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        // Validation de l'unicité personnalisée par école et UE
        $exists = SchoolSubject::where('school_id', $validated['school_id'])
            ->where('subject_id', $validated['subject_id'])
            ->where('teaching_unit_id', $validated['teaching_unit_id'] ?? null)
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Cette matière est déjà associée à cette unité d\'enseignement.'], 422);
        }

        $schoolSubject = SchoolSubject::create($validated);

        return response()->json($schoolSubject->load(['subject', 'teachingUnit']), 201);
    }

    public function show(SchoolSubject $schoolSubject): JsonResponse
    {
        return response()->json($schoolSubject->load(['subject', 'teachingUnit', 'school']));
    }

    public function update(Request $request, SchoolSubject $schoolSubject): JsonResponse
    {
        $validated = $request->validate([
            'teaching_unit_id' => 'nullable|exists:teaching_units,id',
            'custom_name' => 'nullable|string|max:255',
            'code' => 'nullable|string|max:50',
            'color_code' => 'nullable|string|max:7',
            'credits' => 'numeric|min:0',
            'coefficient' => 'numeric|min:0',
            'hours_cm' => 'integer|min:0',
            'hours_td' => 'integer|min:0',
            'hours_tp' => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $schoolSubject->update($validated);

        return response()->json($schoolSubject->load(['subject', 'teachingUnit']));
    }

    public function destroy(SchoolSubject $schoolSubject): JsonResponse
    {
        $schoolSubject->delete();
        return response()->json(null, 204);
    }
}