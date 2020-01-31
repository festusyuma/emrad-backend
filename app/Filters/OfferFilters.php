<?php

namespace Emrad\Filters;

use Emrad\User;

use Emrad\Models\Product;
use Emrad\Filters\QueryFilter;

class OfferFilters extends QueryFilter
{


    /**
     * function triggers for every search by product name
     *
     * @param $level
     *
     * @return Builder $builder
     */
    public function offerTitle($name = ' ')
    {
        return $this->builder->Where('title','like', '%'. $name .'%');
    }

    /**
     * Offer by discount range
     *
     * @param string $range
     *
     * @return Builder $builder
     */
    public function profitMargin($range = "")
    {
        return $this->builder->where("profit_margin", ">=" , (int)$range);
    }

    /**
     * Get offers by companys
     */
    public function byCompany($companyId)
    {
        $users = User::where("company_id", $companyId)->get();
        $collection = collect($users);
        $usersId = $collection->pluck('id');

        $products = Product::whereIn("user_id", $usersId)->get();
        $collection = collect($products);
        $productId = $collection->pluck('id');


        return $this->builder->whereIn('product_id', $productId);
    }
}
