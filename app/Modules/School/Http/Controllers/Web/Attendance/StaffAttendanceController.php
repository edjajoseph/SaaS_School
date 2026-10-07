<?php

namespace App\Modules\School\Http\Controllers\Web\Attendance;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\AttendanceTerminal;
use App\Modules\School\Models\Staff;
use App\Modules\School\Models\StaffAttendance;
use App\Modules\School\Services\StaffAttendancePayrollService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StaffAttendanceController extends Controller
{
    /**
     * Interface de la Borne / Kiosque de pointage
     */
    public function kioskView(Request $request)
    {
        $clientIp = $request->ip();
        
        // Recherche du terminal correspondant à l'IP ou MAC
        $terminal = AttendanceTerminal::where('is_active', true)
            ->where(function ($query) use ($clientIp) {
                $query->where('ip_address', $clientIp);
            })->first();

        return view('School::staff_attendance.kiosk', compact('terminal'));
    }

    /**
     * Traitement instantané du scan (Entrée / Sortie)
     */
    public function handleKioskScan(Request $request, StaffAttendancePayrollService $payrollService)
    {
        $request->validate([
            'badge_uid' => 'required|string',
            'type' => 'required|in:check_in,check_out,auto',
        ]);

        $badge = trim($request->input('badge_uid'));
        $clientIp = $request->ip();

        // 1. Recherche du membre du personnel par badge_uid ou matricule
        $staff = Staff::with('personne')
            ->where('badge_uid', $badge)
            ->orWhere('matricule', $badge)
            ->first();

        if (!$staff) {
            return response()->json([
                'message' => 'Identifiant ou badge inconnu (' . $badge . ').'
            ], 404);
        }

        // 2. Vérification du terminal (Anti-triche)
        $terminal = AttendanceTerminal::where('is_active', true)
            ->where(function ($query) use ($clientIp) {
                $query->where('ip_address', $clientIp);
            })->first();

        $now = Carbon::now();
        $today = $now->format('Y-m-d');

        // 3. Recherche d'un pointage existant aujourd'hui sans heure de sortie
        $attendance = StaffAttendance::where('staff_id', $staff->id)
            ->where('date', $today)
            ->whereNull('check_out')
            ->first();

        $action = $request->input('type');

        // Détermination automatique si le type est 'auto'
        if ($action === 'auto') {
            $action = $attendance ? 'check_out' : 'check_in';
        }

        $staffName = ($staff->personne->nom ?? '') . ' ' . ($staff->personne->prenoms ?? '');

        if ($action === 'check_in') {
            if ($attendance) {
                return response()->json([
                    'message' => 'Vous êtes déjà pointé à l\'entrée aujourd\'hui.'
                ], 422);
            }

            // Enregistrement de l'Arrivée
            $attendance = StaffAttendance::create([
                'school_id' => $staff->school_id ?? 1,
                'staff_id' => $staff->id,
                'terminal_id' => $terminal->id ?? null,
                'date' => $today,
                'check_in' => $now,
                'verification_method' => $terminal->type ?? 'mac_address',
                'mac_address_used' => $terminal->mac_address ?? null,
            ]);

            return response()->json([
                'success' => true,
                'type' => 'check_in',
                'staff_name' => $staffName,
                'time' => $now->format('H:i:s'),
                'message' => 'Pointage d\'ENTRÉE enregistré avec succès.'
            ]);
        } 
        
        if ($action === 'check_out') {
            if (!$attendance) {
                return response()->json([
                    'message' => 'Aucun pointage d\'entrée trouvé aujourd\'hui pour enregistrer la sortie.'
                ], 422);
            }

            // Enregistrement de la Sortie
            $attendance->update([
                'check_out' => $now,
            ]);

            // Calcul du temps réel et déductions
            $payrollService->processAttendance($attendance);

            return response()->json([
                'success' => true,
                'type' => 'check_out',
                'staff_name' => $staffName,
                'time' => $now->format('H:i:s'),
                'message' => 'Pointage de SORTIE enregistré (' . $attendance->effective_hours . ' hrs effectuées).'
            ]);
        }
    }
}