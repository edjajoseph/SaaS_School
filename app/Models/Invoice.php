<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'tenant_id',
        'subscription_id',
        'amount_ht',
        'tax_amount',
        'tax_rate',
        'amount_ttc',
        'currency',
        'status',
        'due_date',
        'paid_at',
    ];

    protected $casts = [
        'amount_ht' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'amount_ttc' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'due_date' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id');
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}