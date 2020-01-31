<?php

namespace Emrad\Models;

use Emrad\Filters\QueryFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockHistory extends Model
{

    protected $fillable = [
        'product_id', 'user_id', 'inventory_id', 'stock_balance', 'new_stock_balance', 'is_depleted'
    ];


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

    public function category()
    {
        return $this->belongsTo(\Emrad\Models\Category::class);
    }

    public function user()
    {
        return $this->belongsTo(\Emrad\User::class);
    }

    public function retailerInventory()
    {
        return $this->belongsTo(\Emrad\RetailerInventory::class, 'id', 'inventory_id');
    }

}
