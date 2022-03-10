<?php

namespace Emrad\Repositories\Contracts;

use Emrad\Models\Wallet;

interface WalletRepositoryInterface extends BaseRepositoryInterface
{
    public function getUserWallet(int $id);
    public function creditWallet(Wallet $wallet, float $amount, $type = '');
    public function history($user_id, $group);
}
