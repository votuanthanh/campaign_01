<?php

namespace App\Repositories\Contracts;

interface RoleInterface extends RepositoryInterface
{
    public function getRoleId($name, $roleType);
}
