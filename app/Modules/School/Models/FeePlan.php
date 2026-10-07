<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeePlan extends Model
{
    use SoftDeletes;

    protected $fillable = ['school_id', 'academic_year_id', 'level_id', 'school_class_id', 'name', 'total_amount', 'is_active'];

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    /**
     * Relation avec la classe spécifique
     */
    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    /**
     * Relation avec les tranches / échéances du plan
     */
    public function items()
    {
        return $this->hasMany(FeePlanItem::class, 'fee_plan_id');
    }

    /**
     * Relation avec l'établissement
     */
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }
}