<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface extends RepositoryInterface
{
    public function active($token);

    public function register($inputs, $roleId);
}
