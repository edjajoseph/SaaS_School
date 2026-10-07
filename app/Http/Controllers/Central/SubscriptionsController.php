<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\Solution;
use App\Models\Plan;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class SubscriptionsController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with(['tenant', 'solution', 'plan'])
            ->latest()
            ->paginate(15);

        return view('central.subscriptions.index', compact('subscriptions'));
    }

    public function create()
    {
        $tenants   = Tenant::all();
        $solutions = Solution::all();
        $plans     = Plan::all();

        return view('central.subscriptions.create', compact('tenants', 'solutions', 'plans'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tenant_id'     => 'required|exists:tenants,id',
            'solution_id'   => 'required|exists:solutions,id',
            'plan_id'       => 'required|exists:plans,id',
            'billing_cycle' => 'required|in:monthly,yearly',
            'tax_rate'      => 'required|numeric|min:0|max:100',
            'currency'      => 'required|string|max:10',
            'starts_at'     => 'required|date',
        ]);

        $plan     = Plan::findOrFail($validated['plan_id']);
        $startsAt = Carbon::parse($validated['starts_at']);

        // 1. Récupération du prix de base depuis le champ 'price' du Plan
        $basePrice = (float) $plan->price;

        // 2. Calcul du Montant HT selon le cycle choisi dans le formulaire
        // Si le prix du plan est mensuel et que le client prend un abonnement annuel
        if ($validated['billing_cycle'] === 'yearly' && $plan->invoice_period === 'month') {
            $amountHt = $basePrice * 12;
        } else {
            $amountHt = $basePrice;
        }

        // 3. Calcul de la TVA et du TTC
        $taxRate   = (float) $validated['tax_rate'];
        $taxAmount = $amountHt * ($taxRate / 100);
        $amountTtc = $amountHt + $taxAmount;

        // 4. Calcul de la date de fin de période
        $durationMonths      = $validated['billing_cycle'] === 'yearly' ? 12 : 1;
        $currentPeriodEndsAt = $startsAt->copy()->addMonths($durationMonths);

        // 5. Calcul de la date de fin d'essai si le plan en propose une
        $trialEndsAt = null;
        if ($plan->trial_period_days > 0) {
            $trialEndsAt = $startsAt->copy()->addDays($plan->trial_period_days);
        }

        // 6. Annulation des souscriptions actives précédentes
        Subscription::where('tenant_id', $validated['tenant_id'])
            ->whereIn('status', ['active', 'trialing'])
            ->update([
                'status'      => 'canceled',
                'canceled_at' => now(),
            ]);

        // 7. Création de la souscription
        $subscription = Subscription::create([
            'tenant_id'                => $validated['tenant_id'],
            'solution_id'              => $validated['solution_id'],
            'plan_id'                  => $plan->id,
            'status'                   => $trialEndsAt ? 'trialing' : 'active',
            'starts_at'                => $startsAt,
            'trial_ends_at'            => $trialEndsAt,
            'current_period_starts_at' => $startsAt,
            'current_period_ends_at'   => $currentPeriodEndsAt,
        ]);

        // 8. Création de la facture
        Invoice::create([
            'number'          => 'INV-' . date('Ymd') . '-' . rand(100, 999),
            'tenant_id'       => $subscription->tenant_id,
            'subscription_id' => $subscription->id,
            'amount_ht'       => $amountHt,
            'tax_rate'        => $taxRate,
            'tax_amount'      => $taxAmount,
            'amount_ttc'      => $amountTtc,
            'currency'        => $validated['currency'] ?? $plan->currency ?? 'XOF',
            'status'          => 'pending',
            'due_date'        => $startsAt->copy()->addDays(14),
        ]);

        return redirect()->route('subscriptions.index')
            ->with('success', 'Souscription enregistrée et facture générée avec succès !');
    }

    public function show(Subscription $subscription)
    {
        $subscription->load(['tenant', 'solution', 'plan', 'invoices.payments']);
        return view('central.subscriptions.show', compact('subscription'));
    }
}