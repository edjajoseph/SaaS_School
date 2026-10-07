<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodTypeItem extends Model
{
    use SoftDeletes;

    protected $fillable = ['period_type_id', 'name', 'code', 'sequence_order'];

    public function periodType(): BelongsTo
    {
        return $this->belongsTo(PeriodType::class);
    }

    public function academicPeriods(): HasMany
    {
        return $this->hasMany(AcademicPeriod::class);
    }
}