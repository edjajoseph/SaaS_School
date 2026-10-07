<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payroll extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'staff_id',
        'staff_contract_id',
        'payroll_number',
        'period_start',
        'period_end',
        'base_salary',
        'total_hours',
        'gross_amount',
        'bonuses_amount',
        'penalties_amount',
        'advances_amount',
        'net_amount',
        'status',
        'payment_date',
        'payment_method',
        'processed_by',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end'   => 'date',
        'payment_date' => 'date',
        'base_salary'  => 'float',
        'total_hours'  => 'float',
        'gross_amount' => 'float',
        'bonuses_amount' => 'float',
        'penalties_amount' => 'float',
        'advances_amount'  => 'float',
        'net_amount'    => 'float',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(StaffContract::class, 'staff_contract_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PayrollItem::class);
    }

    public function processor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}