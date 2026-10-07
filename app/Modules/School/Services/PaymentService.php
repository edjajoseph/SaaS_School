<?php

namespace App\Modules\School\Services;

use App\Modules\School\Models\StudentFeeAccount;
use App\Modules\School\Models\Payment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentService
{
    /**
     * Enregistre un paiement et met à jour l'échéancier de l'étudiant.
     */
    public static function processPayment(
        StudentFeeAccount $account,
        float $amount,
        string $method,
        ?string $reference = null,
        ?int $userId = null,
        ?string $notes = null
    ): Payment {
        return DB::transaction(function () use ($account, $amount, $method, $reference, $userId, $notes) {
            
            // 1. Génération d'un numéro de reçu unique
            $receiptNumber = 'REC-' . date('Ym') . '-' . str_pad(Payment::count() + 1, 5, '0', STR_PAD_LEFT);

            // 2. Création de la transaction de paiement
            $payment = Payment::create([
                'school_id'              => $account->enrollment->school_id,
                'student_fee_account_id' => $account->id,
                'receipt_number'         => $receiptNumber,
                'amount'                 => $amount,
                'payment_method'         => $method,
                'transaction_reference' => $reference,
                'paid_at'                => Carbon::now(),
                'received_by_id'         => $userId,
                'notes'                  => $notes,
            ]);

            // 3. Ventiler l'argent sur l'échéancier de l'étudiant
            $remainingPayment = $amount;
            $schedules = $account->schedules()->where('is_paid', false)->orderBy('due_date', 'asc')->get();

            foreach ($schedules as $schedule) {
                if ($remainingPayment <= 0) {
                    break;
                }

                $dueForThisSchedule = $schedule->amount - $schedule->paid_amount;

                if ($remainingPayment >= $dueForThisSchedule) {
                    $schedule->paid_amount += $dueForThisSchedule;
                    $schedule->is_paid = true;
                    $remainingPayment -= $dueForThisSchedule;
                } else {
                    $schedule->paid_amount += $remainingPayment;
                    $remainingPayment = 0;
                }

                $schedule->save();
            }

            // 4. Recalculer les totaux du compte financier
            $account->total_paid += $amount;
            $account->balance = max(0, ($account->total_due - $account->discount_amount) - $account->total_paid);

            if ($account->balance == 0) {
                $account->status = 'paid';
            } else {
                $account->status = 'partially_paid';
            }

            $account->save();

            return $payment;
        });
    }
}