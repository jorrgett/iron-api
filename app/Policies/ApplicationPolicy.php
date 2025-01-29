<?php

namespace App\Policies;

class ApplicationPolicy extends ApiPolicies
{
    public $user;
    public $permission;

    public function __construct($user)
    {
        $this->user = $user;
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

        return false;
    }
}
