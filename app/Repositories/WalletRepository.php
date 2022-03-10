<?php

namespace Emrad\Repositories;

use Carbon\Carbon;
use DB;
use Emrad\Models\Wallet;
use Emrad\Models\WalletCreditHistory;
use Emrad\Repositories\Contracts\WalletRepositoryInterface;

class WalletRepository extends BaseRepository implements WalletRepositoryInterface
{
    public Wallet $model;
    public WalletCreditHistory $historyModel;

    public function __construct(
        Wallet $model,
        WalletCreditHistory $historyModel
    )
    {
        $this->model = $model;
        $this->historyModel = $historyModel;
    }

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

    public function history($user_id, $group = 'day'): ?\Illuminate\Support\Collection
    {
        try {
            return $this->historyModel
                ->select(DB::raw("sum(amount) as `total`, DATE_FORMAT(created_at, '%d-%m-%Y') period"))
                ->groupby('period')
                ->get();
        } catch (\Exception $e) {
            error_log('repository error');
            error_log($e->getMessage());
            return null;
        }
    }
}
