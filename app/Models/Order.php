<?php

namespace Emrad\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    //

    protected $fillable = [
        'amount', 'reference', 'payment_method', 'user_id', 'card_id'
    ];

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderItems::class);
    }
}
