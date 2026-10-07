<?php

namespace App\Modules\School\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Modules\School\Models\AttendanceTerminal;

class VerifyAttendanceTerminal
{
    public function handle(Request $request, Closure $next)
    {
        $clientIp = $request->ip();
        
        // Exemple d'extraction de l'adresse MAC (ex: via arp en réseau local ou en-tête transmis par l'agent de pointage)
        $clientMac = $request->header('X-Terminal-MAC') ?? $this->getMacFromIp($clientIp);

        $terminal = AttendanceTerminal::where('is_active', true)
            ->where(function ($query) use ($clientIp, $clientMac) {
                $query->where('mac_address', $clientMac)
                      ->orWhere('ip_address', $clientIp);
            })->first();

        if (!$terminal && !auth()->user()->hasRole('super-admin')) {
            return response()->json([
                'error' => 'Pointage refusé. Ce terminal (' . $clientIp . ') n\'est pas autorisé pour l\'émargement.'
            ], 403);
        }

        // Injecter le terminal dans la requête
        $request->attributes->set('terminal', $terminal);

        return $next($request);
    }

    private function getMacFromIp($ip)
    {
        // Commande ARP système pour réseau local
        $mac = shell_exec("arp -an " . escapeshellarg($ip));
        preg_match('/..:..:..:..:..:../', $mac, $matches);
        return $matches[0] ?? null;
    }
}