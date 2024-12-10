<?php

namespace App\Policies;

class AdminPolicy
{
    public $user;
    public $permission;

    public function __construct($user, $permission)
    {
        $this->user = $user;
        $this->permission = $permission;
    }

    /**
     * Determine whether the user can resources models.
     *
     * @return mixed
     */
    public function check()
    {
        $role = (auth()->user()->roles)->select('name')->first() ?: [];

        if (in_array("Super Admin", $role) || in_array("Admin", $role)) {
            return true;
        }

        return false;
    }
}
