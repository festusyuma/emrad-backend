<?php

namespace Emrad\Services;

use Emrad\Models\Wallet;
use Emrad\Util\CustomResponse;

class WalletService
{
    private TransactionService $transactionService;

    public function __construct(
        TransactionService $transactionService
    )
    {
        $this->transactionService = $transactionService;
    }

    private function getUserWallet($user): ?Wallet {
        try {
            $wallet = Wallet::where('user_id', $user->id)->first();
            if (!$wallet) $wallet = new Wallet([
                'balance' => 0,
                'user_id' => $user->id
            ]);

            $wallet->save();
            return $wallet;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function fetchBalance($user): CustomResponse
    {
        try {
            $wallet = $this->getUserWallet($user);
            if (!$wallet) return CustomResponse::failed('error generating wallet');

            return CustomResponse::success($wallet);
        } catch (\Exception $e) {
            return CustomResponse::serverError();
        }
    }

    public function fetchHistory($user): CustomResponse {
        try {
            $wallet = $this->getUserWallet($user);
            if (!$wallet) return CustomResponse::failed('error generating wallet');

            return CustomResponse::success($wallet);
        } catch (\Exception $e) {
            return CustomResponse::serverError();
        }
    }

    public function addCard($user): CustomResponse {
        try {
            $wallet = $this->getUserWallet($user);
            if (!$wallet) return CustomResponse::failed('error generating wallet');

            $transactionRes = $this->transactionService->initTransaction([
                'amount' => 50,
                'email' => $user->email,
                'channels' => ['card']
            ]);

            if (!$transactionRes->success) return $transactionRes;
            $transaction = $transactionRes->data;

            return CustomResponse::success($transaction);
        } catch (\Exception $e) {
            return CustomResponse::serverError();
        }
    }

    public function creditWallet(): CustomResponse {
        try {
            return CustomResponse::success();
        } catch (\Exception $e) {
            return CustomResponse::serverError();
        }
    }
}
