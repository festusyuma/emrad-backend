<?php

namespace Emrad\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed|integer $quantity
 * @property mixed|double $amount
 */
class OrderItems extends Model
{
    //

    protected $fillable = [
        'product_id', 'quantity', 'unit_price', 'amount', 'order_id'
    ];
}
