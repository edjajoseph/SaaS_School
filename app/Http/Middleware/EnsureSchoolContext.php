<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Modules\School\Models\School;

class EnsureSchoolContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // 1. Si Super Admin Central (accès global sans restriction de tenant)
        if ($user->hasRole('super-admin')) {
            return $next($request);
        }

        // 2. Identification du Tenant / Établissement actif
        // Ex: via le domaine/sous-domaine, la session ou l'en-tête X-Tenant-ID
        $currentSchoolId = session('active_school_id') 
            ?? $request->header('X-School-Id') 
            ?? $user->school_id;

        if (!$currentSchoolId) {
            abort(403, "Aucun établissement actif associé à la session.");
        }

        $school = School::find($currentSchoolId);

        if (!$school) {
            abort(404, "Établissement introuvable.");
        }

        // 3. Vérification : L'utilisateur a-t-il au moins un rôle ou une permission sur ce Tenant / Team ?
        $hasAccessToTeam = $user->roles()
            ->wherePivot('team_id', $school->id)
            ->exists();

        if (!$hasAccessToTeam && $user->school_id !== $school->id) {
            abort(403, "Accès refusé : Vous ne possédez aucun droit sur cet établissement.");
        }

        // 4. Mémorisation du contexte global de la Team pour Laratrust
        session(['active_school_id' => $school->id]);

        return $next($request);
    }
}