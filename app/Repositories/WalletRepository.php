<?php

namespace Emrad\Repositories;

use Emrad\Models\Wallet;
use Emrad\Repositories\Contracts\WalletRepositoryInterface;

class WalletRepository extends BaseRepository implements WalletRepositoryInterface
{
    public function getUserWallet($id): ?Wallet
    {
        try {
            $wallet = Wallet::where('user_id', $id)->first();
            if (!$wallet) {
                $wallet = new Wallet([
                    'balance' => 0,
                    'user_id' => $id
                ]);

                $wallet->save();
            }

            return $wallet;
        } catch (\Exception $e) {
            error_log('repository error');
            error_log($e->getMessage());
            return null;
        }
    }

    public function creditWallet($wallet, $amount, $type = ''): ?Wallet
    {
        try {
            $wallet->balance += $amount;
            $wallet->save();

            $wallet->creditHistory()->create([
                'amount' => $amount,
                'type' => $type
            ]);

            return $wallet;
        } catch (\Exception $e) {
            error_log('repository error');
            error_log($e->getMessage());
            return null;
        }
    }
}
