<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentFeeAccount extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'school_id',
        'registration_id',
        'fee_plan_id',
        'total_due',
        'discount_amount',
        'discount_reason',
        'total_paid',
        'balance',
        'status',
    ];

    /**
     * Inscription liée
     */
    public function registration()
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }

    /**
     * Relation indirecte (Has One Through) vers Student
     */
    public function student()
    {
        return $this->hasOneThrough(
            Student::class,
            Registration::class,
            'id',              // Clé étrangère sur registrations (registrations.id)
            'id',              // Clé étrangère sur students (students.id)
            'registration_id', // Clé locale sur student_fee_accounts
            'student_id'       // Clé locale sur registrations
        );
    }

    public function schedules()
    {
        return $this->hasMany(StudentFeeSchedule::class, 'student_fee_account_id')->orderBy('due_date', 'asc');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'student_fee_account_id')->orderBy('paid_at', 'desc');
    }

    public function feePlan()
    {
        return $this->belongsTo(FeePlan::class, 'fee_plan_id');
    }


   
    /**
     * Accesseur pour le solde restant net du compte
     */
    public function getBalanceAttribute()
    {
        // Net Dû = Total Dû - Remise
        $netDue = max(0, $this->total_due - $this->discount_amount);
        
        // Solde = Net Dû - Total Payé (plafonné à 0 si entièrement réglé)
        return max(0, $netDue - $this->total_paid);
    }
    
}