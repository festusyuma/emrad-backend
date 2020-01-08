<?php

namespace Emrad\Models;

use Emrad\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RetailerInventory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id', 'quantity', 'cost_price', 'selling_price', 'is_in_stock'
    ];

    public function company()
    {
        return $this->belongsTo(\Emrad\Models\Company::class);
    }

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
