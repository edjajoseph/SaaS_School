<?php

namespace App\Modules\School\Models;

use Laratrust\Models\Role as LaratrustRole;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends LaratrustRole
{
    use HasFactory, SoftDeletes;

    public $guarded = [];

    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];
}