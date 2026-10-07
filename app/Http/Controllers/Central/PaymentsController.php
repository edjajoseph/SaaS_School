<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class PaymentsController extends Controller
{
    /**
     * Historique global des paiements
     */
    public function index(): View
    {
        $payments = Payment::with(['invoice.tenant'])
            ->latest()
            ->paginate(15);

        return view('central.payments.index', compact('payments'));
    }

    /**
     * Enregistrer un paiement manuel pour une facture
     */
    public function store(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'amount'         => 'required|numeric|min:1',
            'payment_method' => 'required|string', // ex: bank_transfer, mobile_money, cash, card
            'transaction_id' => 'nullable|string|max:255',
        ]);

        // Enregistrement conforme à votre modèle Payment
        $payment = Payment::create([
            'invoice_id'            => $invoice->id,
            'gateway'               => $validated['payment_method'], // Utilise le mode de paiement comme gateway (ex: bank_transfer)
            'transaction_reference' => $validated['transaction_id'] ?? 'TRX-' . strtoupper(uniqid()),
            'amount'                => $validated['amount'],
            'currency'              => $invoice->currency ?? 'XOF',
            'status'                => 'successful', // ou 'paid' selon vos valeurs ENUM
            'raw_response'          => [
                'source'     => 'manual_entry',
                'created_by' => auth()->id(),
                'timestamp'  => now()->toDateTimeString(),
            ],
        ]);

        // Mise à jour du statut de la facture si le solde TTC est atteint
        $totalPaid = $invoice->payments()->where('status', 'successful')->sum('amount');

        if ($totalPaid >= $invoice->amount_ttc) {
            $invoice->update([
                'status'  => 'paid',
                'paid_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Le règlement de ' . number_format($payment->amount, 0, ',', ' ') . ' ' . $payment->currency . ' a été enregistré.');
    }
}