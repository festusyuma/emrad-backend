<?php

namespace Emrad\Services;

use Emrad\Models\Card;
use Emrad\Models\Wallet;
use Emrad\Repositories\Contracts\WalletRepositoryInterface;
use Emrad\User;
use Emrad\Util\CustomResponse;

class WalletService
{
    public TransactionService $transactionService;
    public WalletRepositoryInterface $walletRepo;

    public function __construct(
        TransactionService $transactionService,
        WalletRepositoryInterface $walletRepo
    )
    {
        $this->transactionService = $transactionService;
        $this->walletRepo = $walletRepo;
    }

    public function fetchBalance($user): CustomResponse
    {
        try {
            $wallet = $this->walletRepo->getUserWallet($user->id);
            if (!$wallet) return CustomResponse::failed('error fetching wallet');

            return CustomResponse::success($wallet);
        } catch (\Exception $e) {
            return CustomResponse::serverError();
        }
    }

    public function fetchHistory($user): CustomResponse {
        try {
            $wallet = $this->walletRepo->getUserWallet($user->id);
            if (!$wallet) return CustomResponse::failed('error fetching history');

            return CustomResponse::success($wallet);
        } catch (\Exception $e) {
            return CustomResponse::serverError();
        }
    }

    public function addCard($user): CustomResponse {
        try {
            $wallet = $this->walletRepo->getUserWallet($user->id);
            if (!$wallet) return CustomResponse::failed('error fetching wallet');

            $transactionRes = $this->transactionService->initTransaction(config('transactiontype.new_card'), [
                'amount' => 50,
                'email' => $user->email,
                'channels' => ['card'],
                'user_id' => $user->id
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
