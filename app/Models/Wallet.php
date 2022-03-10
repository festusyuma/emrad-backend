<?php

namespace Emrad\Models;

use Eloquent;
use Emrad\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Post
 *
 * @mixin Eloquent
 * @property int|mixed $balance
 * @property mixed $id
 */
class Wallet extends Model
{

    protected $fillable = [
        'balance', 'user_id',
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cards(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Card::class);
    }

    public function creditHistory(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WalletCreditHistory::class);
    }
}
