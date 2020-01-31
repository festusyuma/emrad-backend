<?php

namespace Emrad\Filters;

use Emrad\Models\Role;
use Emrad\Models\Product;
use Emrad\Filters\QueryFilter;

class CompanyFilters extends QueryFilter
{


    /**
     * function triggers for every search by product name
     *
     * @param $level
     *
     * @return Builder $builder
     */
    public function roleId($roleId = "3")
    {
        return $this->builder->where('role_id', $roleId);

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
}
