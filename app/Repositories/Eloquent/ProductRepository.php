<?php

namespace App\Repositories\Eloquent;

use Exception;
use App\Models\Product;
use App\Repositories\Contracts\ProductInterface;
use App\Exceptions\Api\UnknowException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductRepository extends BaseRepository implements ProductInterface
{

    public function model()
    {
        return Product::class;
    }
}
