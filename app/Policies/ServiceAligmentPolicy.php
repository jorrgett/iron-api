<?php

namespace App\Policies;

use App\Models\User;

class ServiceAligmentPolicy extends ApiPolicies
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
        if (auth()->user()->roles[0]->name == 'Super Admin') {
            return true;
        }

        return $this->checkPermission($this->user, $this->permission);
    }
}
