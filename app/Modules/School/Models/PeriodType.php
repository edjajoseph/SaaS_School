<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PeriodType extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'code', 'description'];

    public function items(): HasMany
    {
        return $this->hasMany(PeriodTypeItem::class)->orderBy('sequence_order');
    }
}