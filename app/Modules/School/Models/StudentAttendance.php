<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentAttendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'schedule_id',
        'registration_id',
        'date',
        'status',
        'late_minutes',
        'reason',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}