<?php

namespace App\Traits\Eloquent;

trait ConvertEmptyStringToNull
{
    public function setAttribute($key, $value)
    {
        $value = is_string($value) && $value === '' ? null : $value;

        return parent::setAttribute($key, $value);
    }
}
