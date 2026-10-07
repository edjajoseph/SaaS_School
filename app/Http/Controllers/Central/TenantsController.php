<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Plan;
use App\Models\Solution;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class TenantsController extends Controller
{
    /**
     * Liste des tenants / clients SaaS
     */
    public function index(): View
    {
        $tenants = Tenant::with(['domains', 'solution', 'subscriptions.plan'])
            ->latest()
            ->paginate(10);

        return view('central.tenants.index', compact('tenants'));
    }

    /**
     * Formulaire de création d'un tenant
     */
    public function create(): View
    {
        $plans = Plan::where('is_active', true)->get();
        $solutions = Solution::all(); // On récupère les solutions (Hôtel, École, RH...)

        return view('central.tenants.create', compact('plans', 'solutions'));
    }

    /**
     * Enregistrement d'un nouveau tenant et de son domaine
     */

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'id'          => 'required|string|alpha_dash|unique:tenants,id',
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'domain'      => 'required|string',
            'status'      => 'required|in:active,suspended,inactive',
            'solution_id' => 'required|exists:solutions,id',
            'plan_id'     => 'nullable|exists:plans,id', // Champ plan_id
        ]);

        // 1. Création du Tenant
        $tenant = Tenant::create([
            'id'          => $validated['id'],
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'status'      => $validated['status'],
            'solution_id' => $validated['solution_id'],
        ]);

        // 2. Création du domaine
        $domainName = str_contains($validated['domain'], '.') 
            ? $validated['domain'] 
            : $validated['domain'] . '.' . config('app.domain', 'saas-hotel.test');

        $tenant->domains()->create([
            'domain' => $domainName,
        ]);

        // 3. Création automatique de l'abonnement (Subscription)
        if (!empty($validated['plan_id'])) {
            $tenant->subscriptions()->create([
                'plan_id'      => $validated['plan_id'],
                'solution_id' => $validated['solution_id'],
                'status'       => 'active',
                'starts_at'    => now(),
                'ends_at'      => now()->addYear(), // ou addMonth() selon votre logique d'engagement
            ]);
        }

        return redirect()->route('tenants.index')
            ->with('success', 'Le client, son domaine et son abonnement ont été créés avec succès.');
    }

    /**
     * Détails d'un tenant
     */
    public function show(Tenant $tenant): View
    {
        $tenant->load(['domains', 'solution', 'subscriptions.plan']);
        $plans = Plan::where('is_active', true)->get();

        return view('central.tenants.show', compact('tenant', 'plans'));
    }

    /**
     * Formulaire d'édition d'un tenant
     */
    public function edit(Tenant $tenant): View
    {
        // 1. Charger les relations nécessaires : domaines, solution et abonnements (avec le plan)
        $tenant->load(['domains', 'solution', 'subscriptions.plan']);

        // 2. Récupérer la liste des solutions et des plans pour les selects du formulaire
        $solutions = Solution::all();
        $plans = Plan::all();

        // 3. Extraction propre du sous-domaine
        $firstDomain = $tenant->domains->first();
        $fullDomain = $firstDomain ? $firstDomain->domain : '';
        
        // Extrait uniquement le préfixe du sous-domaine si le domaine complet est stocké
        $subdomain = str_contains($fullDomain, '.') 
            ? explode('.', $fullDomain)[0] 
            : $fullDomain;

        // 4. Récupérer l'ID du plan actif pour pré-sélectionner l'option dans le formulaire
        $activeSub = $tenant->subscriptions->firstWhere('status', 'active');
        $currentPlanId = $activeSub ? $activeSub->plan_id : null;

        return view('central.tenants.edit', compact(
            'tenant', 
            'subdomain', 
            'solutions', 
            'plans', 
            'currentPlanId'
        ));
    }

    /**
     * Mise à jour du tenant
     */
    public function update(Request $request, Tenant $tenant): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255',
            'domain'      => 'required|string',
            'status'      => 'required|in:active,suspended,inactive',
            'solution_id' => 'required|exists:solutions,id',
            'plan_id'     => 'nullable|exists:plans,id',
        ]);

        // 1. Mise à jour des informations de base du Tenant
        $tenant->update([
            'name'        => $validated['name'],
            'email'       => $validated['email'],
            'status'      => $validated['status'],
            'solution_id' => $validated['solution_id'],
        ]);

        // 2. Mise à jour du domaine principal
        $domainName = str_contains($validated['domain'], '.') 
            ? $validated['domain'] 
            : $validated['domain'] . '.' . config('app.domain', 'saas-hotel.test');

        $domain = $tenant->domains()->first();
        if ($domain) {
            $domain->update(['domain' => $domainName]);
        } else {
            $tenant->domains()->create(['domain' => $domainName]);
        }

        // 3. Gestion de l'abonnement (Subscription)
        if (!empty($validated['plan_id'])) {
            $subscription = $tenant->subscriptions()->firstWhere('status', 'active') 
                ?? $tenant->subscriptions()->latest()->first();

            if ($subscription) {
                // Mettre à jour l'abonnement existant
                $subscription->update([
                    'plan_id'     => $validated['plan_id'],
                    'solution_id' => $validated['solution_id'],
                    'status'      => 'active',
                ]);
            } else {
                // Créer un nouvel abonnement
                $tenant->subscriptions()->create([
                    'plan_id'     => $validated['plan_id'],
                    'solution_id' => $validated['solution_id'], // <--- Ajouté ici
                    'status'      => 'active',
                    'starts_at'   => now(),
                    'ends_at'     => now()->addYear(),
                ]);
            }
        }

        return redirect()->route('tenants.index')
            ->with('success', 'La fiche du client et son abonnement ont été mis à jour avec succès.');
    }

    /**
     * Suppression d'un tenant
     */
    public function destroy(Tenant $tenant): RedirectResponse
    {
        $tenant->delete();

        return redirect()->route('tenants.index')
            ->with('success', 'Le client a été supprimé avec succès.');
    }
}