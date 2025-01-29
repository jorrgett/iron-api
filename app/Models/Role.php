<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $hidden = [
        'guard_name',
        'created_at',
        'updated_at',
        'pivot'
    ];
}