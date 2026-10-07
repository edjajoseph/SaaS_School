<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Subscription;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class FounderDashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // 1. KPIs Principaux
        $totalTenants = Tenant::count();
        $activeSubscriptions = Subscription::whereIn('status', ['active', 'trialing'])
            ->where(function ($q) {
                $q->whereNull('starts_at')
                ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('current_period_ends_at')
                ->orWhere('current_period_ends_at', '>=', now());
            })
            ->count();
        
        // Revenu Mensuel Récurrent (MRR) estimé sur les abonnements actifs
        $mrr = Subscription::where('status', 'active')
            ->join('plans', 'subscriptions.plan_id', '=', 'plans.id')
            ->sum('plans.price');

        // Total des encaissements enregistrés
        $totalRevenue = Payment::where('status', 'successful')->sum('amount');

        // 2. Alertes & Actions requises
        $pendingInvoicesCount = Invoice::where('status', 'pending')->count();
        $expiringSubscriptions = Subscription::where('status', 'active')
            ->whereBetween('current_period_ends_at', [now(), now()->addDays(7)])
            ->with(['tenant', 'plan'])
            ->get();

        // 3. Dernières souscriptions & Derniers règlements
        $recentSubscriptions = Subscription::with(['tenant', 'solution', 'plan'])
            ->latest()
            ->take(5)
            ->get();

        $recentPayments = Payment::with('invoice.tenant')
            ->where('status', 'successful')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.founder', compact(
            'totalTenants',
            'activeSubscriptions',
            'mrr',
            'totalRevenue',
            'pendingInvoicesCount',
            'expiringSubscriptions',
            'recentSubscriptions',
            'recentPayments'
        ));
    }
}
