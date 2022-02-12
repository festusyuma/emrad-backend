<?php

namespace Emrad\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItems extends Model
{
    //

    protected $fillable = [
        'product_id', 'quantity', 'unit_price', 'amount', 'order_id'
    ];
}
