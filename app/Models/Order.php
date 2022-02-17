<?php

namespace Emrad\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 * @property mixed|integer $id
 * @property mixed|double $amount
 * @property bool|mixed $payment_confirmed
 * @property mixed|integer $transaction_id
 */
class Order extends Model
{
    //

    protected $fillable = [
        'amount', 'transaction_id', 'payment_method', 'user_id', 'card_id'
    ];

    public function items(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(OrderItems::class);
    }

    public function transaction(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Transaction::class);
    }
}
