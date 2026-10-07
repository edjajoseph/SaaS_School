<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentItem extends Model
{
    use SoftDeletes;

    protected $fillable = ['payment_id', 'student_fee_schedule_id', 'amount_allocated'];

    public function schedule()
    {
        return $this->belongsTo(StudentFeeSchedule::class, 'student_fee_schedule_id');
    }
}