<?php

namespace App\Policies;

use App\Models\User;

class ErrorPolicy extends ApiPolicies
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

        if (in_array("Super Admin", $role)) {
            return true;
        }

        return $this->checkPermission($this->user, $this->permission);
    }
}
