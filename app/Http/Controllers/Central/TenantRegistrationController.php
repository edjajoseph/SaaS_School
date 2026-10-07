<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Solution;
use App\Models\Subscription;
use App\Models\Invoice;
use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TenantRegistrationController extends Controller
{
    /**
     * Enregistre un nouveau Tenant (Client SaaS) avec sous-domaine, plan et facture.
     */
    public function register(Request $request): JsonResponse
    {
        // 1. Validation des données d'entrée
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'subdomain'    => [
                'required', 
                'string', 
                'alpha_dash', 
                'max:50', 
                'unique:domains,domain'
            ],
            'admin_name'   => ['required', 'string', 'max:255'],
            'admin_email'  => ['required', 'email', 'max:255'],
            'admin_phone'  => ['nullable', 'string', 'max:30'],
            'plan_id'      => ['required', 'exists:plans,id'],
        ]);

        // 2. Vérification du Plan et de la Solution associée
        $plan = Plan::with('solution')->findOrFail($validated['plan_id']);
        
        if (!$plan->is_active) {
            return response()->json([
                'message' => 'Le plan tarifaire sélectionné n\'est pas actif.'
            ], 422);
        }

        $centralDomain = config('tenancy.central_domains.0', 'saas-hotel.test');
        $fullDomain = strtolower($validated['subdomain']) . '.' . $centralDomain;

        try {
            // 3. Transaction pour garantir l'intégrité des données centrales
            $registrationData = DB::transaction(function () use ($validated, $plan, $fullDomain) {
                
                // A. Création du Tenant
                $tenantId = Str::slug($validated['subdomain']);
                
                $tenant = Tenant::create([
                    'id'          => $tenantId,
                    'name'        => $validated['company_name'],
                    'email'       => $validated['admin_email'],
                    'status'      => 'active',
                    'solution_id' => $plan->solution_id,
                    'data'        => [
                        'admin_name'  => $validated['admin_name'],
                        'admin_phone' => $validated['admin_phone'] ?? null,
                    ],
                ]);

                // B. Création du Domaine (stancl/tenancy crée le lien automatiquement)
                $tenant->domains()->create([
                    'domain' => $fullDomain,
                ]);

                // C. Création de la Souscription (Abonnement)
                $trialEndsAt = $plan->trial_period_days > 0 
                    ? now()->addDays($plan->trial_period_days) 
                    : null;

                $periodEndsAt = $plan->invoice_interval === 'year'
                    ? now()->addYears($plan->invoice_period)
                    : now()->addMonths($plan->invoice_period);

                $subscription = Subscription::create([
                    'tenant_id'                => $tenant->id,
                    'solution_id'              => $plan->solution_id,
                    'plan_id'                  => $plan->id,
                    'status'                   => $trialEndsAt ? 'trialing' : 'active',
                    'starts_at'                => now(),
                    'trial_ends_at'            => $trialEndsAt,
                    'current_period_starts_at' => now(),
                    'current_period_ends_at'   => $periodEndsAt,
                ]);

                // D. Génération de la Première Facture
                $invoice = Invoice::create([
                    'number'          => 'INV-' . strtoupper(Str::random(8)),
                    'tenant_id'       => $tenant->id,
                    'subscription_id' => $subscription->id,
                    'amount_ht'       => $plan->price,
                    'tax_amount'      => 0.00, // Ajustez la taxe si nécessaire (ex: TVA 18%)
                    'amount_ttc'      => $plan->price,
                    'currency'        => $plan->currency,
                    'status'          => $trialEndsAt ? 'paid' : 'pending',
                    'due_date'        => now()->addDays(7),
                    'paid_at'         => $trialEndsAt ? now() : null,
                ]);

                return [
                    'tenant'       => $tenant,
                    'domain'       => $fullDomain,
                    'subscription' => $subscription,
                    'invoice'      => $invoice,
                ];
            });

            // 4. Réponse de succès
            return response()->json([
                'success' => true,
                'message' => 'Souscription effectuée et espace client créé avec succès.',
                'data'    => [
                    'tenant_id'   => $registrationData['tenant']->id,
                    'app_url'     => 'https://' . $registrationData['domain'],
                    'plan'        => $plan->name,
                    'status'      => $registrationData['subscription']->status,
                    'invoice_ref' => $registrationData['invoice']->number,
                ]
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'espace client.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}