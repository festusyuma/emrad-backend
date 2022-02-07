<?php

namespace Emrad\Services;

use Emrad\Models\Card;
use Emrad\Models\Wallet;
use Emrad\User;
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

            $transactionRes = $this->transactionService->initTransaction(config('transactiontype.new_card'), [
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

    public function confirmAddCard($user_id, $data): CustomResponse {
        try {
            $user = User::find($user_id);

            $wallet = $this->getUserWallet($user);
            if (!$wallet) return CustomResponse::failed('error fetching wallet');

            $wallet->balance += 50;
            $wallet->save();

            $authorization = $data->authorization;
            $card_data = [
                'last_4' => $authorization->last4,
                'expiration_date' => $authorization->exp_month.'/'.$authorization->exp_year,
                'full_name' => $authorization->account_name,
                'authorization_code' => $authorization->authorization_code,
                'wallet_id' => $wallet->id
            ];
            $card = new Card($card_data);

            return CustomResponse::success($card);
        } catch (\Exception $e) {
            return CustomResponse::serverError();
        }
    }

    public function confirmCreditWallet($user_id, $amount): CustomResponse {
        try {
            $user = User::find($user_id);

            $wallet = $this->getUserWallet($user);
            if (!$wallet) return CustomResponse::failed('error fetching wallet');

            $wallet->balance += $amount;
            $wallet->save();

            return CustomResponse::success($wallet);
        } catch (\Exception $e) {
            return CustomResponse::serverError();
        }
    }
}
