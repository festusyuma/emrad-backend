<?php

namespace Emrad\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RetailerSale extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id', 'quantity', 'unit_price', 'sale_amount', 'created_by'
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
