<?php

namespace App\Policies;

class UserPolicy extends ApiPolicies
{
    public $user;
    public $permission;
    public $own;

    public function __construct($user, $permission, $own = null)
    {
        $this->user = $user;
        $this->permission = $permission;
        $this->own = $own;
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
        if (!empty($this->own) && $this->user == $this->own->id) {
            return true;
        }

        return false;
    }
}
