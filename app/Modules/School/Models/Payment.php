<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Payment extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $fillable = ['school_id', 'student_fee_account_id', 'receipt_number', 'amount', 'payment_method', 'transaction_reference', 'paid_at', 'received_by_id', 'status', 'cancellation_reason', 'cancelled_by_id', 'cancelled_at', 'notes'];

    protected $casts = [
        'paid_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Relation avec le compte financier de l'étudiant
     */
    public function account()
    {
        return $this->belongsTo(StudentFeeAccount::class, 'student_fee_account_id');
    }

    /**
     * Relation avec le journal de trésorerie (Caisse / Banque)
     */
    public function journal()
    {
        return $this->belongsTo(AccountingJournal::class, 'journal_id');
    }

    /**
     * Relation avec l'utilisateur / caissier qui a effectué la saisie
     */
    public function cashier()
    {
        return $this->belongsTo(User::class, 'received_by_id');
    }

    /**
     * Relation avec les éléments de ventilation du paiement
     */
    public function items()
    {
        return $this->hasMany(PaymentItem::class, 'payment_id');
    }
}