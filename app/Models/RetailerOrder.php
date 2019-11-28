<?php

namespace Emrad\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RetailerOrder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id', 'company_id', 'created_by', 'quantity', 'unit_price', 'order_amount'
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
