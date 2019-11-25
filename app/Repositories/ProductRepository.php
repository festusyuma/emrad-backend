<?php

namespace Emrad\Repositories;

use Emrad\Models\Product;
use Emrad\Repositories\Contracts\ProductRepositoryInterface;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface {

    public $model;

    /**
     * ProductRepository Constructor
     *
     * @param Emrad\Models\Product $product
      */
    public function __construct(Product $product)
    {
        $this->model = $product;
    }
}
