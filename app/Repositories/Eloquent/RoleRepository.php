<?php

namespace App\Repositories\Eloquent;

use App\Models\Role;
use App\Repositories\Contracts\RoleInterface;

class RoleRepository extends BaseRepository implements RoleInterface
{
    public function model()
    {
        return Role::class;
    }

    public function findRoleId($name, $typeRole)
    {
        if (!$name || !$typeRole) {
            return false;
        }

        $roleId = $this->where([
            'name' => $name,
            'type' => $typeRole,
        ])->get();
        
        return !$roleId->isEmpty() ? $roleId : false;
    }
}
