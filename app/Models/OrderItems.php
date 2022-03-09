<?php

namespace Emrad\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 * @property mixed|integer $quantity
 * @property mixed|double $amount
 */
class OrderItems extends Model
{
    //

    protected $fillable = [
        'product_id', 'quantity', 'unit_price', 'amount', 'order_id'
    ];

    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function order(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
