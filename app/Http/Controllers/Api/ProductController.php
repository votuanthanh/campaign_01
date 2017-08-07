<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Repositories\Contracts\ProductInterface;

class ProductController extends ApiController
{
    protected $productRepository;

    public function __construct(
        ProductInterface $productRepository
    ) {
        parent::__construct();
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        return $this->doAction(function () {
            $this->compacts['products'] = $this->productRepository->all();
        });
    }
}
