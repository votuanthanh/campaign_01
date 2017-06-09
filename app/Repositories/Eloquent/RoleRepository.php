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

    /**
     * Get role by name and type
     * Give $roleNames = '*' for all roles
     * Give $idOnly = true for id array, false for collection
     * @param  string, array  $roleNames
     * @param  int  $roleType
     * @return mixed
     */
    public function getRoleByName($roleNames, $roleType)
    {
        $roleNames = is_array($roleNames) ? $roleNames : [$roleNames];

        if (!$roleNames || !$roleType) {
            return null;
        }

        $roles = $this->where('type', $roleType);

        if ($roleNames != ['*']) {
            $roles = $roles->whereIn('name', $roleNames);
        }

        return $roles = $roles->lists('id');
    }
}
