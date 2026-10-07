<?php

namespace App\Policies;

use App\Models\User;

class PaymentPolicy
{
    /**
     * Droit d'encaisser un règlement.
     */
    public function create(User $user): bool
    {
        return $user->isAbleTo('payments-create');
    }

    /**
     * Droit d'annuler ou modifier un paiement.
     */
    public function delete(User $user): bool
    {
        return $user->isAbleTo('payments-delete');
    }
}