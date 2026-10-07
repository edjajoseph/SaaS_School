<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayrollSetting extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'payroll_settings';

    protected $fillable = ['school_id', 'country_id', 'apply_taxes_to_vacants'];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function taxRules(): HasMany
    {
        return $this->hasMany(PayrollTaxRule::class, 'school_id', 'school_id');
    }

    /**
     * Pays associé (Réglementation fiscale / sociale)
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class,'country_id');
    }
}