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
        'type', 'amount', 'status', 'verified', 'reference', 'source'
    ];
}
