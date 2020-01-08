<?php

namespace Emrad\Models;

use Emrad\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    public function product()
    {
        return $this->belongsTo(\Emrad\Models\Product::class);
    }

    /**
     * scope for quering product
     *
     * @param $query
     * @param QueryFilter $filters
     */
    public function scopeFilter($query, QueryFilter $filters)
    {
        return $filters->apply($query);
    }
}
