<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $invoices = Invoice::with(['tenant', 'subscription.plan'])
            ->when($request->tenant_id, fn($q) => $q->where('tenant_id', $request->tenant_id))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->paginate(15);

        return response()->json($invoices);
    }

    public function show(Invoice $invoice): JsonResponse
    {
        return response()->json($invoice->load(['tenant', 'subscription', 'payments']));
    }

    public function markAsPaid(Invoice $invoice): JsonResponse
    {
        if ($invoice->status === 'paid') {
            return response()->json(['message' => 'La facture est déjà réglée.'], 422);
        }

        $invoice->update([
            'status'  => 'paid',
            'paid_at' => now(),
        ]);

        // Mettre à jour le statut du souscription associée si nécessaire
        if ($invoice->subscription && $invoice->subscription->status === 'past_due') {
            $invoice->subscription->update(['status' => 'active']);
        }

        return response()->json([
            'message' => 'Facture marquée comme payée.',
            'invoice' => $invoice,
        ]);
    }
}