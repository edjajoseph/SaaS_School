<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PayrollTaxRule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'code',
        'name',
        'category',
        'calculation_type',
        'rate',
        'fixed_amount',
        'ceiling',
        'min_base',
        'is_active',
        'applies_to_vacants',
    ];

    protected $casts = [
        'rate'               => 'float',
        'fixed_amount'       => 'float',
        'ceiling'            => 'float',
        'min_base'           => 'float',
        'is_active'          => 'boolean',
        'applies_to_vacants' => 'boolean',
    ];
}