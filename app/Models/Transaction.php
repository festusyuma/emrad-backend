<?php

namespace Emrad\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;

/**
 * Post
 *
 * @mixin Eloquent
 */
class Transaction extends Model
{
    protected $fillable = [
        'type', 'amount', 'reference', 'card_id', 'user_id', 'verified', 'status'
    ];
}
