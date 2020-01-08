<?php

namespace Emrad\Filters;

use Emrad\Filters\QueryFilter;
use Emrad\Models\Offer;

class OfferFilters extends QueryFilter
{


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
