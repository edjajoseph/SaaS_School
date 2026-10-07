<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MobilePaymentWebhookController extends Controller
{
    /**
     * Webhook générique / point d'entrée unique ou séparé par passerelle
     */

    // 1. Webhook Wave
    public function handleWave(Request $request): JsonResponse
    {
        Log::info('Webhook Wave reçu :', $request->all());

        // Vérification de la signature HMAC de Wave
        $waveSignature = $request->header('wave-signature');
        $webhookSecret = config('services.wave.webhook_secret');

        if ($webhookSecret && !$this->verifyWaveSignature($request->getContent(), $waveSignature, $webhookSecret)) {
            Log::warning('Signature Wave invalide.');
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        $type = $request->input('type');
        $data = $request->input('data');

        if ($type === 'checkout.session.completed') {
            $transactionRef = $data['id'] ?? null;
            $customRef = $data['client_reference'] ?? null; // ID de la facture ou référence interne
            $amount = $data['amount'] ?? 0;

            $this->processSuccessfulPayment(
                gateway: 'wave',
                invoiceId: (int) $customRef,
                transactionRef: $transactionRef,
                amount: (float) $amount,
                rawPayload: $request->all()
            );
        }

        return response()->json(['status' => 'success']);
    }

    // 2. Webhook CinetPay
    public function handleCinetPay(Request $request): JsonResponse
    {
        Log::info('Webhook CinetPay reçu :', $request->all());

        $cpayTransId = $request->input('cpay_transaction_id');
        $siteId = $request->input('cpay_site_id');
        $customParam = $request->input('cpay_custom'); // ID de la facture passé lors de l'initialisation
        $token = $request->input('token');

        if (!$cpayTransId || $siteId !== config('services.cinetpay.site_id')) {
            return response()->json(['message' => 'Données CinetPay invalides'], 400);
        }

        // Vérification du statut auprès de l'API CinetPay (fortement recommandé par CinetPay)
        $paymentStatus = $this->verifyCinetPayStatus($cpayTransId);

        if ($paymentStatus && $paymentStatus['code'] === '00') {
            $this->processSuccessfulPayment(
                gateway: 'cinetpay',
                invoiceId: (int) ($customParam ?? $paymentStatus['data']['metadata']),
                transactionRef: $cpayTransId,
                amount: (float) $paymentStatus['data']['amount'],
                rawPayload: $request->all()
            );

            return response()->json(['status' => 'ACCEPTED']);
        }

        return response()->json(['status' => 'REFUSED'], 400);
    }

    // 3. Webhook Orange Money (OM Web Payment)
    public function handleOrangeMoney(Request $request): JsonResponse
    {
        Log::info('Webhook Orange Money reçu :', $request->all());

        // OM envoie généralement un événement avec status SUCCESS/FAILED
        $status = $request->input('status');
        $orderId = $request->input('order_id'); // Ex: ID de facture
        $txnid = $request->input('txnid');
        $amount = $request->input('amount');

        if ($status === 'SUCCESS' || $status === 'SUCCESSFUL') {
            $this->processSuccessfulPayment(
                gateway: 'orange_money',
                invoiceId: (int) $orderId,
                transactionRef: $txnid,
                amount: (float) $amount,
                rawPayload: $request->all()
            );
        }

        return response()->json(['status' => 'OK']);
    }

    /**
     * Traitement métier d'un paiement réussi (Communs à toutes les passerelles)
     */
    private function processSuccessfulPayment(
        string $gateway,
        int $invoiceId,
        string $transactionRef,
        float $amount,
        array $rawPayload
    ): void {
        DB::transaction(function () use ($gateway, $invoiceId, $transactionRef, $amount, $rawPayload) {
            
            $invoice = Invoice::with(['subscription', 'tenant'])->find($invoiceId);

            if (!$invoice) {
                Log::error("Facture #{$invoiceId} non trouvée lors du webhook {$gateway}.");
                return;
            }

            // Éviter le double traitement si la facture est déjà marquée payée
            if ($invoice->status === 'paid') {
                Log::info("La facture #{$invoice->id} est déjà marquée comme payée.");
                return;
            }

            // 1. Enregistrement ou mise à jour du Payment
            Payment::updateOrCreate(
                ['transaction_reference' => $transactionRef],
                [
                    'invoice_id'   => $invoice->id,
                    'gateway'      => $gateway,
                    'amount'       => $amount,
                    'currency'     => $invoice->currency ?? 'XOF',
                    'status'       => 'successful',
                    'raw_response' => $rawPayload,
                ]
            );

            // 2. Mise à jour de la Facture
            $invoice->update([
                'status'  => 'paid',
                'paid_at' => now(),
            ]);

            // 3. Mettre à jour et Réactiver la Souscription du Tenant
            if ($invoice->subscription) {
                $subscription = $invoice->subscription;

                // Calculer la nouvelle fin de période
                $plan = $subscription->plan;
                $newPeriodEndsAt = $plan->invoice_interval === 'year'
                    ? now()->addYears($plan->invoice_period)
                    : now()->addMonths($plan->invoice_period);

                $subscription->update([
                    'status'                   => 'active',
                    'current_period_starts_at' => now(),
                    'current_period_ends_at'   => $newPeriodEndsAt,
                ]);

                // Si le Tenant était suspendu, le réactiver
                if ($invoice->tenant && $invoice->tenant->status !== 'active') {
                    $invoice->tenant->update(['status' => 'active']);
                }
            }

            Log::info("Paiement validé avec succès pour la facture #{$invoice->id} via {$gateway}.");
        });
    }

    /**
     * Vérification de la signature Wave (HMAC SHA256)
     */
    private function verifyWaveSignature(string $payload, ?string $signatureHeader, string $secret): bool
    {
        if (!$signatureHeader) return false;

        $parts = explode(',', $signatureHeader);
        $timestamp = null;
        $signatures = [];

        foreach ($parts as $part) {
            [$key, $value] = explode('=', trim($part), 2);
            if ($key === 't') $timestamp = $value;
            if ($key === 'v1') $signatures[] = $value;
        }

        $signedPayload = "{$timestamp}.{$payload}";
        $expectedSignature = hash_hmac('sha256', $signedPayload, $secret);

        return in_array($expectedSignature, $signatures, true);
    }

    /**
     * Vérification du statut auprès de l'API de contrôle CinetPay
     */
    private function verifyCinetPayStatus(string $transactionId): ?array
    {
        try {
            $response = \Illuminate\Support\Facades\Http::post('https://api-checkout.cinetpay.com/v2/payment/check', [
                'apikey'         => config('services.cinetpay.api_key'),
                'site_id'        => config('services.cinetpay.site_id'),
                'transaction_id' => $transactionId,
            ]);

            return $response->json();
        } catch (\Throwable $e) {
            Log::error('Erreur de vérification CinetPay: ' . $e->getMessage());
            return null;
        }
    }
}