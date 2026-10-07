<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $subscriptions = Subscription::with(['tenant', 'solution', 'plan'])
            ->when($request->tenant_id, fn($q) => $q->where('tenant_id', $request->tenant_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->paginate(15);

        return response()->json($subscriptions);
    }

    public function show(Subscription $subscription): JsonResponse
    {
        return response()->json($subscription->load(['tenant', 'solution', 'plan', 'invoices']));
    }

    /**
     * Changer de plan (Upgrade / Downgrade)
     */
    public function changePlan(Request $request, Subscription $subscription): JsonResponse
    {
        $validated = $request->validate([
            'plan_id' => ['required', 'exists:plans,id'],
        ]);

        $newPlan = Plan::findOrFail($validated['plan_id']);

        $subscription->update([
            'plan_id'     => $newPlan->id,
            'solution_id' => $newPlan->solution_id,
        ]);

        return response()->json([
            'message'      => 'Abonnement mis à jour avec le nouveau plan.',
            'subscription' => $subscription->load('plan'),
        ]);
    }

    /**
     * Annuler un abonnement
     */
    public function cancel(Subscription $subscription): JsonResponse
    {
        $subscription->update([
            'status'      => 'canceled',
            'canceled_at' => now(),
            'cancels_at'  => $subscription->current_period_ends_at ?? now(),
        ]);

        return response()->json([
            'message'      => 'Abonnement annulé.',
            'subscription' => $subscription,
        ]);
    }
}