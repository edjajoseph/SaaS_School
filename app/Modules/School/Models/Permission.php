<?php

namespace App\Modules\School\Models;

use Laratrust\Models\Permission as LaratrustPermission;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends LaratrustPermission
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'module',
        'feature',
    ];
}
