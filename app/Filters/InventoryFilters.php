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
        $productId = Product::where('category_id', $categoryId)->id;
        return $this->builder->where('product_id', $productId);
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
        return $this->builder->Where('name','like', '%'. $name .'%');
    }
}
