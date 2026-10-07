
<?php

namespace App\Modules\School\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\School\Models\StaffAttendance;
use App\Modules\School\Models\Staff;
use App\Modules\School\Services\StaffAttendancePayrollService;
use Illuminate\Http\Request;

class BiometricAttendanceController extends Controller
{
    public function handleScan(Request $request, StaffAttendancePayrollService $service)
    {
        $request->validate([
            'badge_uid' => 'required_without:staff_id',
            'staff_id' => 'required_without:badge_uid',
            'timestamp' => 'required|date',
            'verification_method' => 'required|in:biometric,badge,mac_address,manual',
        ]);

        $staff = Staff::where('badge_uid', $request->badge_uid)
            ->orWhere('id', $request->staff_id)
            ->first();

        if (!$staff) {
            return response()->json(['message' => 'Membre du personnel non trouvé'], 444);
        }

        $scanTime = \Carbon\Carbon::parse($request->timestamp);
        $date = $scanTime->format('Y-m-d');

        // Vérifier s'il s'agit d'une entrée ou d'une sortie
        $attendance = StaffAttendance::where('staff_id', $staff->id)
            ->where('date', $date)
            ->whereNull('check_out')
            ->first();

        if (!$attendance) {
            // Nouveau Check-in (Entrée)
            $attendance = StaffAttendance::create([
                'school_id' => $staff->school_id,
                'staff_id' => $staff->id,
                'date' => $date,
                'check_in' => $scanTime,
                'verification_method' => $request->verification_method,
                'mac_address_used' => $request->header('X-Terminal-MAC'),
            ]);
            $type = 'ENTRY';
        } else {
            // Check-out (Sortie)
            $attendance->update([
                'check_out' => $scanTime,
            ]);
            
            // Re-calcul du temps effectif et des retenues
            $service->processAttendance($attendance);
            $type = 'EXIT';
        }

        return response()->json([
            'success' => true,
            'type' => $type,
            'staff' => $staff->personne->nom . ' ' . $staff->personne->prenoms,
            'time' => $scanTime->format('H:i:s'),
        ]);
    }
}