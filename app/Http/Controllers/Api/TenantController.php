<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $tenants = Tenant::with(['solution', 'domains', 'subscriptions.plan'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->paginate(15);

        return response()->json($tenants);
    }

    public function show(Tenant $tenant): JsonResponse
    {
        return response()->json(
            $tenant->load(['solution', 'domains', 'subscriptions.plan', 'invoices.payments'])
        );
    }

    public function updateStatus(Request $request, Tenant $tenant): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:active,suspended,cancelled'],
        ]);

        $tenant->update(['status' => $validated['status']]);

        return response()->json([
            'message' => "Statut du tenant mis à jour avec succès ({$tenant->status}).",
            'tenant'  => $tenant,
        ]);
    }

    public function destroy(Tenant $tenant): JsonResponse
    {
        // Supprime la base de données tenant liée via Stancl/Tenancy
        $tenant->delete();

        return response()->json(['message' => 'Tenant et sa base de données supprimés avec succès.']);
    }
}