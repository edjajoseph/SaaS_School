<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayComponent extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'code',
        'name',
        'type',
        'calculation_method',
        'value',
        'applies_to',
        'is_active',
    ];

    protected $casts = [
        'value'     => 'float',
        'is_active' => 'boolean',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }
}