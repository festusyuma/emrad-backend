<?php

namespace Emrad\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RetailerInventory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id', 'in_stock', 'cost_price', 'selling_price', 'in_stock', 'out_of_stock', 'reserved'
    ];

    public function company()
    {
        return $this->belongsTo(\Emrad\Models\Company::class);
    }

    public function product()
    {
        return $this->belongsTo(\Emrad\Models\Product::class);  
    }
}
