<?php

namespace Emrad\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * Post
 *
 * @mixin Eloquent
 * @property mixed $reference
 * @property mixed|string $status
 * @property integer $card_id
 * @property bool $verified
 */
class Transaction extends Model
{
    protected $fillable = [
        'type', 'amount', 'reference', 'card_id', 'user_id', 'verified', 'status', 'payment_method'
    ];
}
