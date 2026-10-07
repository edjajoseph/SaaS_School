<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StaffRole extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'school_id',
        'code',
        'name',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(StaffContract::class, 'staff_role_id');
    }
}