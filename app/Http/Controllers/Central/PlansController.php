<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Solution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlansController extends Controller
{
    public function index(): View
    {
        $plans = Plan::with('solution')
            ->withCount('subscriptions')
            ->latest()
            ->paginate(10);

        return view('central.plans.index', compact('plans'));
    }

    public function create(): View
    {
        $solutions = Solution::where('is_active', true)->get();
        return view('central.plans.create', compact('solutions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'solution_id'    => 'required|exists:solutions,id',
            'name'           => 'required|string|max:255',
            'slug'           => 'required|string|max:50|unique:plans,slug|alpha_dash',
            'price'          => 'required|numeric|min:0',
            'currency'       => 'required|string|size:3',
            'invoice_period' => 'required|in:monthly,yearly',
            'description'    => 'nullable|string',
            'is_active'      => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Plan::create($validated);

        return redirect()
            ->route('plans.index')
            ->with('success', 'Le plan tarifaire a été créé avec succès.');
    }

    /**
     * Affiche les détails d'un plan tarifaire spécifique.
     */
    public function show(Plan $plan): View
    {
        $plan->load(['solution', 'subscriptions.tenant']);

        return view('central.plans.show', compact('plan'));
    }

    public function edit(Plan $plan): View
    {
        $solutions = Solution::where('is_active', true)->get();
        return view('central.plans.edit', compact('plan', 'solutions'));
    }

    public function update(Request $request, Plan $plan): RedirectResponse
    {
        $validated = $request->validate([
            'solution_id'    => 'required|exists:solutions,id',
            'name'           => 'required|string|max:255',
            'slug'           => 'required|string|max:50|alpha_dash|unique:plans,slug,' . $plan->id,
            'price'          => 'required|numeric|min:0',
            'currency'       => 'required|string|size:3',
            'invoice_period' => 'required|in:monthly,yearly',
            'description'    => 'nullable|string',
            'is_active'      => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $plan->update($validated);

        return redirect()
            ->route('plans.index')
            ->with('success', 'Le plan tarifaire a été mis à jour avec succès.');
    }

    public function destroy(Plan $plan): RedirectResponse
    {
        if ($plan->subscriptions()->exists()) {
            return redirect()
                ->route('plans.index')
                ->with('error', 'Impossible de supprimer ce plan : des abonnements y sont actuellement souscrits.');
        }

        $plan->delete();

        return redirect()
            ->route('plans.index')
            ->with('success', 'Le plan tarifaire a été supprimé.');
    }
}