<?php

namespace App\Http\Middleware;

use App\Models\Subscription;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantIsActive
{
    /**
     * Traite la requête entrante sur l'espace Tenant.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Récupération du tenant courant via Stancl/Tenancy
        $tenant = tenant();

        // Si la requête n'est pas exécutée dans le contexte d'un tenant, laisser passer
        if (!$tenant) {
            return $next($request);
        }

        // 2. Vérification du statut global du Tenant
        if ($tenant->status === 'suspended') {
            return $this->denyAccess(
                $request, 
                'Votre compte établissement a été suspendu. Veuillez contacter le support.',
                'tenant.suspended'
            );
        }

        if ($tenant->status === 'cancelled') {
            return $this->denyAccess(
                $request, 
                'Votre compte a été résilié.',
                'tenant.cancelled'
            );
        }

        // 3. Vérification de l'Abonnement (Subscription)
        // Récupération du dernier abonnement rattaché à ce tenant et à la solution
        $subscription = Subscription::where('tenant_id', $tenant->id)
            ->where('solution_id', $tenant->solution_id)
            ->latest()
            ->first();

        if (!$subscription) {
            return $this->denyAccess(
                $request, 
                'Aucun abonnement actif associé à cet établissement.',
                'tenant.no-subscription'
            );
        }

        // 4. Contrôle de la validité de l'abonnement
        if (!$this->isSubscriptionValid($subscription)) {
            
            // Marquer automatiquement la souscription en retard si expirée
            if ($subscription->status !== 'past_due' && $subscription->status !== 'canceled') {
                $subscription->update(['status' => 'past_due']);
            }

            return $this->denyAccess(
                $request, 
                'Votre abonnement a expiré ou le paiement est en attente. Veuillez régulariser votre facture.',
                'tenant.subscription-expired',
                ['invoice_needed' => true]
            );
        }

        return $next($request);
    }

    /**
     * Vérifie si la souscription est en état d'accès autorisé.
     */
    private function isSubscriptionValid(Subscription $subscription): bool
    {
        // Période d'essai valide
        if ($subscription->status === 'trialing') {
            return $subscription->trial_ends_at === null || $subscription->trial_ends_at->isFuture();
        }

        // Abonnement actif et date de période non expirée
        if ($subscription->status === 'active') {
            return $subscription->current_period_ends_at === null || $subscription->current_period_ends_at->isFuture();
        }

        return false;
    }

    /**
     * Retourne une réponse d'interdiction (JSON pour API, Redirection/Vue pour web).
     */
    private function denyAccess(Request $request, string $message, string $reasonCode, array $extra = []): Response
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(array_merge([
                'success' => false,
                'error'   => 'access_denied',
                'reason'  => $reasonCode,
                'message' => $message,
            ], $extra), 403);
        }

        // Redirection vers la page centrale ou une vue de blocage d'abonnement
        return response()->view('errors.tenant-suspended', [
            'message'    => $message,
            'reasonCode' => $reasonCode,
        ], 403);
    }
}