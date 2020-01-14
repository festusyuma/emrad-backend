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
        return $this->builder->where("profit_margin", ">" , (int)$range);
    }
}
