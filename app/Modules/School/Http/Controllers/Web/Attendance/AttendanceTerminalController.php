<?php

namespace App\Modules\School\Http\Controllers\Web\Attendance;

use App\Http\Controllers\Controller;

use App\Modules\School\Models\AttendanceTerminal;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttendanceTerminalController extends Controller
{
    /**
     * Liste des pointeuses & bornes de l'établissement
     */
    public function index()
    {
        $terminals = AttendanceTerminal::latest()->get();

        return view('School::staff_attendance.terminals.index', compact('terminals'));
    }

    /**
     * Enregistrement d'une nouvelle borne
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location_description' => 'nullable|string|max:255',
            'type' => 'required|in:biometric,rfid_card,pc_station,qr_scanner',
            'mac_address' => 'nullable|string|max:17',
            'ip_address' => 'nullable|ip',
            'is_active' => 'boolean',
        ]);

        $validated['school_id'] = auth()->user()->school_id ?? 1;
        $validated['api_token'] = Str::random(60);
        $validated['is_active'] = $request->has('is_active');

        AttendanceTerminal::create($validated);

        return redirect()->back()
            ->with('success', 'Borne d\'émargement ajoutée avec succès.');
    }

    /**
     * Mise à jour des informations d'une borne
     */
    public function update(Request $request, AttendanceTerminal $terminal)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location_description' => 'nullable|string|max:255',
            'type' => 'required|in:biometric,rfid_card,pc_station,qr_scanner',
            'mac_address' => 'nullable|string|max:17',
            'ip_address' => 'nullable|ip',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $terminal->update($validated);

        return redirect()->back()
            ->with('success', 'Informations de la borne mises à jour.');
    }

    /**
     * Régénération de l'API Token pour la sécurité de l'agent distant
     */
    public function regenerateToken(AttendanceTerminal $terminal)
    {
        $terminal->update([
            'api_token' => Str::random(60),
        ]);

        return redirect()->back()
            ->with('success', 'Le jeton d\'API a été régénéré avec succès.');
    }

    /**
     * Suppression (SoftDelete) d'un terminal
     */
    public function destroy(AttendanceTerminal $terminal)
    {
        $terminal->delete();

        return redirect()->back()
            ->with('success', 'Terminal supprimé du système.');
    }
}