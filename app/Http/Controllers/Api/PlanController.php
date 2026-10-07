<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Plan::with('solution');

        if ($request->has('solution_id')) {
            $query->where('solution_id', $request->query('solution_id'));
        }

        return response()->json($query->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'solution_id'       => ['required', 'exists:solutions,id'],
            'name'              => ['required', 'string', 'max:255'],
            'code'              => ['required', 'string', 'unique:plans,code', 'max:50'],
            'price'             => ['required', 'numeric', 'min:0'],
            'currency'          => ['nullable', 'string', 'size:3'],
            'invoice_period'    => ['required', 'integer', 'min:1'],
            'invoice_interval'  => ['required', 'in:month,year'],
            'trial_period_days' => ['integer', 'min:0'],
            'is_active'         => ['boolean'],
        ]);

        $plan = Plan::create($validated);

        return response()->json($plan, 201);
    }

    public function show(Plan $plan): JsonResponse
    {
        return response()->json($plan->load('solution'));
    }

    public function update(Request $request, Plan $plan): JsonResponse
    {
        $validated = $request->validate([
            'solution_id'       => ['sometimes', 'exists:solutions,id'],
            'name'              => ['sometimes', 'string', 'max:255'],
            'code'              => ['sometimes', 'string', 'max:50', 'unique:plans,code,' . $plan->id],
            'price'             => ['sometimes', 'numeric', 'min:0'],
            'currency'          => ['nullable', 'string', 'size:3'],
            'invoice_period'    => ['sometimes', 'integer', 'min:1'],
            'invoice_interval'  => ['sometimes', 'in:month,year'],
            'trial_period_days' => ['sometimes', 'integer', 'min:0'],
            'is_active'         => ['boolean'],
        ]);

        $plan->update($validated);

        return response()->json($plan);
    }

    public function destroy(Plan $plan): JsonResponse
    {
        if ($plan->subscriptions()->exists()) {
            return response()->json([
                'message' => 'Impossible de supprimer un plan lié à des abonnements actifs.'
            ], 422);
        }

        $plan->delete();

        return response()->json(['message' => 'Plan supprimé avec succès.']);
    }
}