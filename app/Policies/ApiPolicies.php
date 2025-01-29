<?php

namespace App\Policies;

use App\Models\User;
use App\Repositories\User\UserRepository;
use Spatie\Permission\Models\Role;

abstract class ApiPolicies
{
    protected function getRoleUser($user_id)
    {
        $userRepository = new UserRepository(new User());
        $user = $userRepository->getByField('id', $user_id);

        return $user->getRoleNames();
    }

    protected function checkPermission($user_id, $permission)
    {

        $role = $this->getRoleUser($user_id);
        $verify = array();

        for ($i = 0; $i < count($role); $i++) {
            $role_own[$i] =  Role::findByName($role[$i]);
            $verify[$i] = $role_own[$i]->hasPermissionTo($permission);
        }

        if (in_array(true, $verify)) {
            return true;
        }

        return false;
    }
}
