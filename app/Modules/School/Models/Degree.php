<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Degree extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'level',
        'is_active',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
    ];

    public function staff(): HasMany
    {
        return $this->hasMany(Staff::class, 'degree_id');
    }
}