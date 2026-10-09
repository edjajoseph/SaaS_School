<?php

namespace App\Modules\School\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\School\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index(Request $request)
    {
        /** @var \App\Modules\School\Models\User $user */
        $user = Auth::user();

        // 1. Super Administrateur / Administrateur Système
        
        if (
            $user->hasRole('super-admin') || $user->hasRole('admin-ecole')) 
        {
            $data = $this->dashboardService->getAdminMetrics();

            return view('School::dashboard.admin', $data);
        }

        // 2. Directeur Académique / Responsable Pédagogique
        if ($user->hasRole('manager')) {
            $data = $this->dashboardService->getAcademicMetrics();
            return view('School::dashboard.academic', $data);
        }

        // 2. Directeur Académique / Responsable Pédagogique
        if ($user->hasRole('directeur-etudes')) {
            $data = $this->dashboardService->getAcademicMetrics();
            return view('School::dashboard.academic', $data);
        }

        // 3. Service Scolarité & Vie Scolaire
        if ($user->hasRole('secretaire')) {
            $data = $this->dashboardService->getScolariteMetrics();
            return view('School::dashboard.scolarite', $data);
        }

        // 4. Agent de Caisse / Comptable
        if ($user->hasRole('comptable')) {
            $data = $this->dashboardService->getCaisseMetrics();
            return view('School::dashboard.caisse', $data);
        }

        // 5. Enseignant / Intervenant
        if ($user->hasRole('enseignant')) {
            $data = $this->dashboardService->getTeacherMetrics($user);
            return view('School::dashboard.teacher', $data);
        }

        // 6. Étudiant / Parent
        if ($user->hasRole('etudiant')) {
            $data = $this->dashboardService->getStudentMetrics($user);
            return view('School::dashboard.student', $data);
        }

        abort(403, 'Accès non autorisé : aucun profil rôle valide n’est associé à votre compte.');
    }
}