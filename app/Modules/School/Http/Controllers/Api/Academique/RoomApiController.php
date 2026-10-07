<?php

namespace App\Modules\School\Http\Controllers\Api\Academique;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoomApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Room::with('school');

        if ($request->filled('school_id')) {
            $query->where('school_id', $request->input('school_id'));
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
            'school_id' => ['required', 'exists:schools,id'],
            'name'      => [
                'required', 
                'string', 
                'max:255',
                Rule::unique('rooms')->where(fn ($q) => $q->where('school_id', $request->school_id)),
            ],
            'code'      => ['nullable', 'string', 'max:50'],
            'capacity'  => ['nullable', 'integer', 'min:1'],
            'building'  => ['nullable', 'string', 'max:255'],
            'floor'     => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $room = Room::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Salle créée avec succès.',
            'data'    => $room->load('school'),
        ], 201);
    }

    public function show(Room $room): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $room->load(['school', 'schedules']),
        ]);
    }

    public function update(Request $request, Room $room): JsonResponse
    {
        $validated = $request->validate([
            'school_id' => ['sometimes', 'exists:schools,id'],
            'name'      => [
                'sometimes', 
                'string', 
                'max:255',
                Rule::unique('rooms')
                    ->where(fn ($q) => $q->where('school_id', $request->input('school_id', $room->school_id)))
                    ->ignore($room->id),
            ],
            'code'      => ['nullable', 'string', 'max:50'],
            'capacity'  => ['nullable', 'integer', 'min:1'],
            'building'  => ['nullable', 'string', 'max:255'],
            'floor'     => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $room->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Salle mise à jour avec succès.',
            'data'    => $room->load('school'),
        ]);
    }

    public function toggleActive(Room $room): JsonResponse
    {
        $room->update(['is_active' => !$room->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Statut de la salle mis à jour.',
            'data'    => ['id' => $room->id, 'is_active' => $room->is_active],
        ]);
    }

    public function destroy(Room $room): JsonResponse
    {
        $room->delete();

        return response()->json([
            'success' => true,
            'message' => 'Salle supprimée avec succès.',
        ]);
    }
}