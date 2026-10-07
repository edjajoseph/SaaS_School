<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HourlyRate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'staff_id',
        'rate_cm',
        'rate_td',
        'rate_tp',
    ];

    protected $casts = [
        'rate_cm' => 'decimal:2',
        'rate_td' => 'decimal:2',
        'rate_tp' => 'decimal:2',
    ];

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
    
}