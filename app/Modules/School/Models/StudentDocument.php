<?php

namespace App\Modules\School\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentDocument extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'registration_id',
        'document_type_id',
        'title',
        'is_provided',
        'file_path',
        'remarks',
    ];

    protected $casts = [
        'is_provided' => 'boolean',
    ];

    
    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }
    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}