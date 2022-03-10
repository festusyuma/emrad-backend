<?php

namespace Emrad\Models;

use Illuminate\Database\Eloquent\Model;

class WalletCreditHistory extends Model
{
    protected $fillable = [
        'wallet_id', 'amount', 'type'
    ];

    public function wallet(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }
}
