<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'solution_id',
        'name',
        'code',
        'price',
        'currency',
        'invoice_period',
        'invoice_interval',
        'trial_period_days',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'invoice_period' => 'integer',
        'trial_period_days' => 'integer',
        'is_active' => 'boolean',
    ];

    public function solution(): BelongsTo
    {
        return $this->belongsTo(Solution::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}