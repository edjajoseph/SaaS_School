<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'registrations';

    protected $fillable = [
        'school_id',
        'academic_year_id',
        'student_id',
        'school_class_id',
        'registration_number',
        'type',
        'status',
        'payment_receipt',
        'registration_date',
        'notes',
    ];

    protected $casts = [
        'registration_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    /**
     * Documents déposés ou associés à cette session d'inscription.
     */
    public function documents(): HasMany
    {
        return $this->hasMany(StudentDocument::class);
    }

    /**
     * Compte financier de l'étudiant pour cette inscription.
     */
    public function feeAccount(): HasOne
    {
        return $this->hasOne(StudentFeeAccount::class, 'registration_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeForCurrentYear($query, $academicYearId)
    {
        return $query->where('academic_year_id', $academicYearId);
    }

    public function scopeForSchool($query, $schoolId)
    {
        return $query->where('school_id', $schoolId);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Génère automatiquement un numéro de reçu / inscription unique.
     * Ex: REG-2026-0001
     */
    public static function generateRegistrationNumber(): string
    {
        $year = date('Y');
        $prefix = "REG-{$year}-";
        
        $latest = self::where('registration_number', 'like', "{$prefix}%")
                      ->withTrashed()
                      ->orderBy('id', 'desc')
                      ->first();

        if (!$latest) {
            return $prefix . '0001';
        }

        $number = (int) substr($latest->registration_number, strlen($prefix));
        return $prefix . str_pad($number + 1, 4, '0', STR_PAD_LEFT);
    }
}