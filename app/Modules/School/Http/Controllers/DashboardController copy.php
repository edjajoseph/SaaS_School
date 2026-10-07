<?php

namespace App\Modules\School\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // 1. Redirection pour l'Administrateur
        if ($user->hasRole('admin-ecole') || $user->hasRole('super-admin')) {
            return redirect()->action([\App\Modules\School\Http\Controllers\Dashboard\AdminDashboardController::class, 'index']);
        }

         // 2. Redirection pour le Directeur des Etudes
         if ($user->hasRole('directeur-etudes') || $user->hasRole('directeur')) {
            return redirect()->action([\App\Modules\School\Http\Controllers\Dashboard\DirEtudeDashboardController::class, 'index']);
        }

        // 2. Redirection pour l'Enseignant
        if ($user->hasRole('enseignant') || $user->hasRole('teacher')) {
            return redirect()->action([\App\Modules\School\Http\Controllers\Dashboard\TeacherDashboardController::class, 'index']);
        }

         // 2. Redirection pour le Comptable
         if ($user->hasRole('comptable')) {
            return redirect()->action([\App\Modules\School\Http\Controllers\Dashboard\ComptableDashboardController::class, 'index']);
        }

         // 2. Redirection pour le Secretaire / Scolarité
         if ($user->hasRole('secretaire')) {
            return redirect()->action([\App\Modules\School\Http\Controllers\Dashboard\TeacherDashboardController::class, 'index']);
        }

        // 3. Redirection pour l'Étudiant (si applicable)
        if ($user->hasRole('etudiant') || $user->hasRole('student')) {
            return redirect()->action([\App\Modules\School\Controllers\Dashboard\StudentDashboardController::class, 'index']);
        }

        // Dashboard par défaut si aucun rôle spécifique n'est trouvé
        return view('School::dashboard');
    }
}