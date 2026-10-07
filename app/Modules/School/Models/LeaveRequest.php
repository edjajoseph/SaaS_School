<?php

namespace App\Modules\School\Models;

use App\Modules\School\Models\User;
use App\Modules\School\Models\School;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class LeaveRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_id',
        'user_id',
        'type',
        'start_date',
        'end_date',
        'reason',
        'document_path',
        'status',
        'processed_by',
        'processed_at',
        'rejection_reason',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'processed_at' => 'datetime',
    ];

    /**
     * Scope pour restreindre aux demandes d'une école spécifique
     */
    public function scopeForSchool(Builder $query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}