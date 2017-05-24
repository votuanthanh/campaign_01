<?php

namespace App\Repositories\Eloquent;

use App\Models\Type;
use App\Repositories\Contracts\TypeInterface;

class TypeRepository extends BaseRepository implements TypeInterface
{
    public function model()
    {
        return Type::class;
    }
}
