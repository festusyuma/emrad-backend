<?php

namespace Emrad\Repositories;

use Emrad\Models\Product;
use Emrad\Repositories\Contracts\ProductRepositoryInterface;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface {

    public Product $model;

    /**
     * ProductRepository Constructor
     *
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->model = $product;
    }

    public function paginateAllByUser($user_id, $limit, $relations = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->model::with($relations)
            ->where('user_id', $user_id)
            ->orderBy('created_at', 'DESC')->paginate($limit);
    }
}
