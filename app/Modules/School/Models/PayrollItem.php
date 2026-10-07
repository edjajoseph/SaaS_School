<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollItem extends Model
{
    protected $fillable = [
        'payroll_id',
        'pay_component_id',
        'label',
        'type',
        'base_amount',
        'rate_or_value',
        'amount',
    ];

    protected $casts = [
        'base_amount'   => 'float',
        'rate_or_value' => 'float',
        'amount'        => 'float',
    ];

    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class);
    }

    public function component(): BelongsTo
    {
        return $this->belongsTo(PayComponent::class, 'pay_component_id');
    }
}