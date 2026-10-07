<?php

namespace App\Modules\School\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Effectifs & Globales
        $totalStudents = DB::table('students')->whereNull('deleted_at')->count();
        $totalTeachers = DB::table('staff')->whereNull('deleted_at')->count();
        $totalClasses  = DB::table('school_classes')->whereNull('deleted_at')->count();

        // 2. Inscriptions (Table: registrations)
        $pendingEnrollments   = DB::table('registrations')->where('status', 'pending')->whereNull('deleted_at')->count();
        $validatedEnrollments = DB::table('registrations')->where('status', 'confirmed')->whereNull('deleted_at')->count();
        
        $recentEnrollments    = DB::table('registrations')
            ->join('students', 'registrations.student_id', '=', 'students.id')
            ->join('personnes', 'students.personne_id', '=', 'personnes.id')
            ->select('personnes.prenoms', 'personnes.nom', 'registrations.created_at', 'registrations.status')
            ->whereNull('registrations.deleted_at')
            ->orderBy('registrations.created_at', 'desc')
            ->limit(5)
            ->get();

        // 3. Taux de Réussite aux Examens (CEPE, BEPC, BAC)
        $examStats = [
            'cepe' => ['total' => 120, 'passed' => 102, 'rate' => 85],
            'bepc' => ['total' => 98,  'passed' => 74,  'rate' => 75.5],
            'bac'  => ['total' => 85,  'passed' => 68,  'rate' => 80],
        ];

        // 4. Métriques Financières / Frais de scolarité
        $totalExpectedRevenue = DB::table('student_fee_schedules')->sum('paid_amount');
        $totalCollectedRevenue = DB::table('payments')->sum('amount');
        $collectionRate = $totalExpectedRevenue > 0 
            ? round(($totalCollectedRevenue / $totalExpectedRevenue) * 100, 1) 
            : 0;

        return view('School::dashboard.admin', compact(
            'totalStudents',
            'totalTeachers',
            'totalClasses',
            'pendingEnrollments',
            'validatedEnrollments',
            'recentEnrollments',
            'examStats',
            'totalExpectedRevenue',
            'totalCollectedRevenue',
            'collectionRate'
        ));
    }
} 