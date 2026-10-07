<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    protected $fillable = [
        'id',
        'name',
        'email',
        'status',
        'solution_id',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    /**
     * Déclaration explicite des colonnes SQL de la table 'tenants'
     * (Évite de les stocker au format JSON dans la colonne 'data')
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'email',
            'status',
            'solution_id',
        ];
    }

    public function solution(): BelongsTo
    {
        return $this->belongsTo(Solution::class, 'solution_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'tenant_id', 'id');
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'tenant_id', 'id');
    }
}