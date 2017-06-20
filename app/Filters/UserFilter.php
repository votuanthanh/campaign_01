<?php

namespace App\Filters;

class UserFilter extends QueryFilter
{
    public function name($name)
    {
        return $this->builder->where('name', $name);
    }

    public function gender($gender)
    {
        return $this->builder->where('gender', $gender);
    }
}
