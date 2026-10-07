<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Support\Facades\Log;

class CinetPayController extends Controller
{
    /**
     * Notification IPN appelée directement par CinetPay
     */
    public function handleNotification(Request $request)
    {
        // 1. Récupération de l'identifiant de transaction envoyé par CinetPay
        $cpayId = $request->input('cpay_id');
        $transactionId = $request->input('cpay_custom'); // ID de votre facture ou transaction

        if (!$cpayId) {
            return response()->json(['status' => 'error', 'message' => 'No transaction ID'], 400);
        }

        // 2. Vérification du statut de la transaction via l'API CinetPay (recommandé)
        // ... (Appel API CinetPay pour valider le statut réel) ...

        // Exemple si le paiement est valide :
        $invoice = Invoice::find($transactionId);

        if ($invoice) {
            // Création ou mise à jour du paiement dans votre BDD
            $payment = Payment::updateOrCreate(
                ['transaction_reference' => $cpayId],
                [
                    'invoice_id'   => $invoice->id,
                    'gateway'      => 'cinetpay',
                    'amount'       => $request->input('cpay_amount'),
                    'currency'     => $request->input('cpay_currency', 'XOF'),
                    'status'       => 'successful',
                    'raw_response' => $request->all(), // Sauvegarde la réponse JSON brute
                ]
            );

            // Mise à jour du statut de la facture
            if ($invoice->payments()->where('status', 'successful')->sum('amount') >= $invoice->amount_ttc) {
                $invoice->update([
                    'status'  => 'paid',
                    'paid_at' => now(),
                ]);
            }

            return response()->json(['status' => 'success', 'message' => 'Payment recorded']);
        }

        return response()->json(['status' => 'error', 'message' => 'Invoice not found'], 404);
    }
}