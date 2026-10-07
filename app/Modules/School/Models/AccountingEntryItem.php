<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountingEntryItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'accounting_entry_id',
        'chart_of_account_id',
        'label',
        'debit',
        'credit',
        'lettering_code',
        'is_reconciled',
        'reconciled_at',
    ];

    protected $casts = [
        'debit'         => 'decimal:2',
        'credit'        => 'decimal:2',
        'is_reconciled' => 'boolean',
        'reconciled_at' => 'datetime',
    ];

    /**
     * En-tête de la pièce comptable
     */
    public function entry(): BelongsTo
    {
        return $this->belongsTo(AccountingEntry::class, 'accounting_entry_id');
    }

    /**
     * Compte du plan comptable affecté
     */
    public function chartOfAccount(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'chart_of_account_id');
    }

    /**
     * Marque la ligne comme rapprochée
     */
    public function markAsReconciled(): bool
    {
        return $this->update([
            'is_reconciled' => true,
            'reconciled_at' => now(),
        ]);
    }

    /**
     * Annule le rapprochement de la ligne
     */
    public function unmarkAsReconciled(): bool
    {
        return $this->update([
            'is_reconciled' => false,
            'reconciled_at' => null,
        ]);
    }
}