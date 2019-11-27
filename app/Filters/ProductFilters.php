<?php

namespace Emrad\Filters;

use Emrad\Filters\QueryFilter;
use Emrad\Models\Category;

class ProductFilters extends QueryFilter
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
        return $this->builder->where('category_id', $categoryId);
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

    /**
     * search by popular
     *
     *
     * @return Builder $builder
     */
    public function popular()
    {
        return $this->builder->orderBy('views_count', 'desc');
    }

    /**
     * search by trending
     *
     *
     * @return Builder $builder
     */
    public function trending()
    {
        return $this->builder->withCount('fans')->orderBy('fans_count', 'desc');
    }

    /**
     * Search product base on queryString
     */
    public function global($queryString)
    {
        $merchantId = \Emrad\Models\Merchant::whereLike(['user.first_name', 'user.last_name', 'user.username'], $queryString)->pluck('id');

        return $this->builder->whereLike([
                                            'name',
                                            'category.name',
                                            'description',
                                            'category.description',
                                            'category.slug',
                                            'merchant.business_name'
                                        ], $queryString)
                                        ->orWhereIn('merchant_id', $merchantId);

    }

    /**
     * Order product by date
     *
     */
    public function orderByDate()
    {
        return $this->builder->orderBy('id', 'desc');
    }
}
