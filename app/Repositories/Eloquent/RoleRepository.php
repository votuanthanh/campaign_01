<?php

namespace App\Repositories\Eloquent;

use App\Models\Role;
use App\Models\User;
use App\Repositories\Contracts\RoleInterface;

class RoleRepository extends BaseRepository implements RoleInterface
{
    public function model()
    {
        return Role::class;
    }

    public function getRoleId($name, $roleType)
    {
        if (!$name || !$roleType) {
            return false;
        }

        return $this->where([
            'name' => $name,
            'type' => $roleType,
        ])->lists('id')->first();
    }
}
