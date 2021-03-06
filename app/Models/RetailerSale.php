<?php

namespace Emrad\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RetailerSale extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id', 'user_id', 'quantity', 'fmcg_selling_price', 'amount_sold', 'sale_amount', 'created_by'
    ];

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Emrad\Models\Company::class);
    }

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\Emrad\Models\Product::class);
    }

}
