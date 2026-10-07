<?php

namespace App\Models;

use Laratrust\Models\Role as LaratrustRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends LaratrustRole
{
    use HasFactory;

    public $guarded = [];

    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];
}