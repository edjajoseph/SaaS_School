<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(): JsonResponse
    {
        $payments = Payment::with('invoice.tenant')->latest()->paginate(15);
        return response()->json($payments);
    }

    /**
     * Enregistrer manuellement un paiement (Ex: Virement bancaire, Espèces, Webhook)
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'invoice_id'            => ['required', 'exists:invoices,id'],
            'gateway'               => ['required', 'string', 'max:50'], // Ex: 'cinetpay', 'wave', 'bank_transfer'
            'transaction_reference' => ['nullable', 'string', 'max:255'],
            'amount'                => ['required', 'numeric', 'min:0'],
            'currency'              => ['nullable', 'string', 'size:3'],
            'status'                => ['required', 'in:successful,failed,pending'],
            'raw_response'          => ['nullable', 'array'],
        ]);

        $payment = DB::transaction(function () use ($validated) {
            $payment = Payment::create($validated);

            // Si le paiement est validé, passer la facture en payée
            if ($payment->status === 'successful') {
                $invoice = Invoice::findOrFail($payment->invoice_id);
                $invoice->update([
                    'status'  => 'paid',
                    'paid_at' => now(),
                ]);
            }

            return $payment;
        });

        return response()->json($payment->load('invoice'), 201);
    }
}