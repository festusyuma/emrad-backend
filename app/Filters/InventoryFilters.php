<?php

namespace Emrad\Filters;

use Emrad\Models\Product;
use Emrad\Models\Category;
use Emrad\Filters\QueryFilter;

class InventoryFilters extends QueryFilter
{
    /**
     * function triggers for every search by category
     *
     * @param $categoryName
     *
     * @return Builder $builder
     */
    public function category($categorySlug)
    {
        $categoryId = Category::where('slug', $categorySlug)->first()->id;

        $product = Product::where('category_id', $categoryId)->get();

        $collection = collect($product);
        $productId = $collection->pluck('id');


        return $this->builder->whereIn('product_id', $productId);
    }

    /**
     * function triggers for every search by product name
     *
     * @param $level
     *
     * @return Builder $builder
     */
    public function productName($name = ' ')
    {
        $product = Product::Where('name','like', '%'. $name .'%')->get();

        $collection = collect($product);
        $productId = $collection->pluck('id');

        return $this->builder->whereIn('product_id', $productId);
        // return $this->builder->Where('name','like', '%'. $name .'%');
    }
}
