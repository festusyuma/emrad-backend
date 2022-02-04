<?php

namespace Emrad\Services;

use Emrad\Models\Wallet;
use Emrad\Util\CustomResponse;

class WalletService
{
    public $userId;

    public function __construct($user)
    {
        try {
            $wallet = Wallet::where('user_id', $user->id)->first();
            dd($wallet);
        } catch (\Exception $e) {
            return CustomResponse::serverError();
        }
    }

    public function fetchBalance() {

    }

    public function fetchHistory() {

    }
}
