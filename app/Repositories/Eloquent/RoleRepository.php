<?php

namespace App\Repositories\Eloquent;

use App\Models\Role;
use App\Models\User;
use App\Repositories\Contracts\RoleInterface;
use App\Exceptions\Api\NotFoundException;

class RoleRepository extends BaseRepository implements RoleInterface
{
    public function model()
    {
        return Role::class;
    }

    public function findRoleOrFail($name, $typeRole)
    {
        $role = $this->where([
            'name' => $name,
            'type' => $typeRole,
        ])->first();

        if (is_null($role)) {
            throw new NotFoundException('Role' . $name . 'not exists in system');
        }

        return $role;
    }
}
