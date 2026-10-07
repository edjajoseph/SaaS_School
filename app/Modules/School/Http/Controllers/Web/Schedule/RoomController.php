<?php

namespace App\Modules\School\Http\Controllers\Web\Schedule;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\Room;
use App\Modules\School\Models\School;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RoomController extends Controller
{
    /**
     * Liste des salles avec filtres et recherche.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole('admin-ecole') || $user->hasRole('super-admin') || $user->is_admin;

        $query = Room::with('school');

        // Multi-tenant isolation
        if ($isAdmin) {
            if ($request->filled('school_id')) {
                $query->forSchool($request->input('school_id'));
            }
            $schools = School::orderBy('name')->get();
        } else {
            $query->forSchool($user->school_id);
            $schools = collect();
        }

        // Filtre Statut (Actif / Inactif)
        if ($request->filled('is_active')) {
            $query->where('is_active', filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN));
        }

        // Recherche multi-champs
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('building', 'like', "%{$search}%");
            });
        }

        $rooms = $query->latest()->paginate(15)->withQueryString();

        return view('School::schedule.rooms.index', compact('rooms', 'schools', 'isAdmin'));
    }

    /**
     * Formulaire de création.
     */
    public function create(Request $request)
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole('admin-ecole') || $user->hasRole('super-admin') || $user->is_admin;
        $schools = $isAdmin ? School::orderBy('name')->get() : collect();

        $viewData = compact('schools', 'isAdmin');

        if ($request->ajax()) {
            return view('School::schedule.rooms.create', $viewData);
        }

        return view('School::schedule.rooms.create', $viewData);
    }

    /**
     * Enregistrement d'une nouvelle salle.
     */
    public function store(Request $request)
    {
        $schoolId = $this->resolveSchoolId($request);

        $validated = $request->validate([
            'school_id' => ['required', 'exists:schools,id'],
            'name'      => [
                'required', 'string', 'max:255',
                Rule::unique('rooms')->where(fn ($q) => $q->where('school_id', $schoolId)->whereNull('deleted_at')),
            ],
            'code'      => [
                'nullable', 'string', 'max:50',
                Rule::unique('rooms')->where(fn ($q) => $q->where('school_id', $schoolId)->whereNull('deleted_at')),
            ],
            'capacity'  => 'nullable|integer|min:1',
            'building'  => 'nullable|string|max:255',
            'floor'     => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ], [
            'name.unique' => 'Une salle porte déjà ce nom dans cet établissement.',
            'code.unique' => 'Ce code de salle est déjà utilisé dans cet établissement.',
        ]);

        $validated['school_id'] = $schoolId;
        $validated['is_active'] = $request->has('is_active');

        $room = Room::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'La salle a été créée avec succès.',
                'room'    => $room
            ]);
        }

        return redirect()->route('schedule.rooms.index')->with('success', 'La salle a été créée avec succès.');
    }

    /**
     * Affichage d'une salle.
     */
    public function show(Request $request, Room $room)
    {
        $room->load(['school', 'schedules']);

        if ($request->ajax()) {
            return view('School::schedule.rooms.show', compact('room'));
        }

        return view('School::schedule.rooms.show', compact('room'));
    }

    /**
     * Formulaire d'édition.
     */
    public function edit(Request $request, Room $room)
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole('admin-ecole') || $user->hasRole('super-admin') || $user->is_admin;
        $schools = $isAdmin ? School::orderBy('name')->get() : collect();

        
        if ($request->ajax()) {
            return view('School::schedule.rooms.edit', compact('room', 'schools', 'isAdmin'));
        }

        return view('School::schedule.rooms.edit', compact('room', 'schools', 'isAdmin'));
    }



    /**
     * Mise à jour de la salle.
     */
    public function update(Request $request, Room $room) // Préférez le singulier $room
{
    // 1. S'assurer que l'ID est bien un entier pour l'exclusion
    $roomId = (int) $room->id;
    $targetSchoolId = $request->input('school_id') ?? $this->resolveSchoolId($request, $room);

    // 2. Nettoyer le champ code s'il est envoyé vide
    if ($request->has('code') && empty($request->input('code'))) {
        $request->merge(['code' => null]);
    }

    $validated = $request->validate([
        'school_id' => 'required|exists:schools,id',
        'name'      => [
            'required', 'string', 'max:255',
            Rule::unique('rooms', 'name')
                ->ignore($roomId)
                ->where(fn ($q) => $q->where('school_id', $targetSchoolId)->whereNull('deleted_at')),
        ],
        'code'      => [
            'nullable', 'string', 'max:50',
            Rule::unique('rooms', 'code')
                ->ignore($roomId)
                ->where(fn ($q) => $q->where('school_id', $targetSchoolId)->whereNull('deleted_at')),
        ],
        'capacity'  => 'nullable|integer|min:1',
        'building'  => 'nullable|string|max:255',
        'floor'     => 'nullable|integer',
        'is_active' => 'nullable|boolean',
    ], [
        'name.unique' => 'Une salle porte déjà ce nom dans cet établissement.',
        'code.unique' => 'Ce code de salle est déjà utilisé dans cet établissement.',
    ]);

    $validated['school_id'] = $targetSchoolId;
    $validated['is_active'] = $request->has('is_active');

    $room->update($validated);

    if ($request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'La salle a été mise à jour avec succès.',
            'rooms'   => $room
        ]);
    }

    return redirect()->route('schedule.rooms.index')->with('success', 'La salle a été mise à jour avec succès.');
}

    /**
     * Basculer l'état actif/inactif.
     */
    public function toggleActive(Room $room)
    {
        $room->update(['is_active' => ! $room->is_active]);
        $status = $room->is_active ? 'activée' : 'désactivée';

        return redirect()->back()->with('success', "La salle {$room->name} a été {$status}.");
    }

    /**
     * Suppression SoftDelete.
     */
    public function destroy(Room $rooms)
    {
        if ($rooms->schedules()->exists()) {
            return redirect()->back()->with('error', 'Impossible de supprimer cette salle car des emplois du temps y sont rattachés.');
        }

        $rooms->delete();

        return redirect()->route('schedule.rooms.index')->with('success', 'La salle a été supprimée avec succès.');
    }

    /**
     * Détermine le school_id selon l'utilisateur et la requête.
     */
    private function resolveSchoolId(Request $request, ?Room $rooms = null): int
    {
        $user = auth()->user();
        if ($user->hasRole('admin-ecole') || $user->hasRole('super-admin') || $user->is_admin) {
            return (int) ($request->school_id ?? $rooms?->school_id);
        }
        return (int) $user->school_id;
    }
}