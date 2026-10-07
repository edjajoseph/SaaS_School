<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentFeeSchedule extends Model
{
    use SoftDeletes;

    protected $fillable = ['student_fee_account_id', 'fee_plan_item_id', 'label','original_amount','discount_amount', 'amount', 'paid_amount', 'due_date', 'is_paid', 'is_blocking'];

    protected $casts = [
        'due_date' => 'date',
        'is_paid' => 'boolean',
        'is_blocking' => 'boolean',
    ];

    
    public function account()
    {
        return $this->belongsTo(StudentFeeAccount::class, 'student_fee_account_id');
    }
   
    /**
     * Relation vers l'élément de plan tarifaire (si applicable)
     */
    public function feePlanItem()
    {
        return $this->belongsTo(FeePlanItem::class, 'fee_plan_item_id');
    }

    // Relation distante pour accéder directement au FeePlan depuis le Schedule
    public function feePlan()
    {
        return $this->hasOneThrough(
            FeePlan::class,
            FeePlanItem::class,
            'id',               // Clé primaire sur fee_plan_items
            'id',               // Clé primaire sur fee_plans
            'fee_plan_item_id', // Clé étrangère sur student_fee_schedules
            'fee_plan_id'       // Clé étrangère sur fee_plan_items
        );
    }
}