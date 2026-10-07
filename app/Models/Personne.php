<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Modules\School\Models\Student;
use App\Modules\School\Models\Staff;

class Personne extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenoms',
        'sexe',
        'telephone',
        'email',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function getNomCompletAttribute(): string
    {
        return trim("{$this->prenoms} {$this->nom}");
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'personne_id');
    }

    public function staff()
    {
        return $this->hasOne(Staff::class, 'personne_id');
    }
}