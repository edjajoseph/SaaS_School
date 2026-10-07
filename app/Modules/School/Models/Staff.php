<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Staff extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'staff';

    protected $fillable = [
        'personne_id',
        'school_id',
        'staff_code',
        'speciality_id',
        'degree_id',
        'is_active',
    ];

    public function personne(): BelongsTo
    {
        return $this->belongsTo(Personne::class, 'personne_id');
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function speciality(): BelongsTo
    {
        return $this->belongsTo(Speciality::class, 'speciality_id');
    }

    public function degree(): BelongsTo
    {
        return $this->belongsTo(Degree::class, 'degree_id');
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(StaffContract::class, 'staff_id');
    }

    public function activeContracts(): HasMany
    {
        return $this->hasMany(StaffContract::class, 'staff_id')
                    ->where('status', StaffContract::STATUS_ACTIVE);
    }
}