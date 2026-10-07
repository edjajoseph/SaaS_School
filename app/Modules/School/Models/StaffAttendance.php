<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaffAttendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'staff_id',
        'schedule_id',
        'terminal_id',
        'date',
        'check_in',
        'check_out',
        'late_minutes',
        'early_leave_minutes',
        'effective_hours',
        'verification_method',
        'mac_address_used',
        'is_deducted',
        'notes',
    ];

    protected $casts = [
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'is_deducted' => 'boolean',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function terminal()
    {
        return $this->belongsTo(AttendanceTerminal::class);
    }
}