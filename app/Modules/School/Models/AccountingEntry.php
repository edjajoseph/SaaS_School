<?php

namespace App\Modules\School\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccountingEntry extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'fiscal_year_id',
        'journal_id',
        'entry_number',
        'entry_date',
        'label',
        'source_type',
        'source_id',
        'created_by_id',
    ];

    protected $casts = [
        'entry_date' => 'date',
    ];

    /**
     * Exercice comptable de la pièce
     */
    public function fiscalYear(): BelongsTo
    {
        return $this->belongsTo(FiscalYear::class);
    }

    /**
     * Journal comptable de la pièce
     */
    public function journal(): BelongsTo
    {
        return $this->belongsTo(AccountingJournal::class, 'journal_id');
    }

    /**
     * Utilisateur ayant créé l'écriture
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    /**
     * Lignes de débit/crédit formant la pièce
     */
    public function items(): HasMany
    {
        return $this->hasMany(AccountingEntryItem::class);
    }

    /**
     * Modèle source ayant déclenché l'écriture (ex: Payment, StudentFeeAccount, etc.)
     */
    public function source(): MorphTo
    {
        return $this->morphTo();
    }
}