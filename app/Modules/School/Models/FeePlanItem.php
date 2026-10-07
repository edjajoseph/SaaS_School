<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeePlanItem extends Model
{
    use SoftDeletes;

    protected $fillable = ['fee_plan_id', 'label', 'amount', 'due_date', 'position', 'is_blocking'];

    protected $casts = [
        'due_date' => 'date',
        'is_blocking' => 'boolean',
    ];


    public function feePlan()
    {
        return $this->belongsTo(FeePlan::class, 'fee_plan_id');
    }

    
}