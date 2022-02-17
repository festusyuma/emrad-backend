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
}
