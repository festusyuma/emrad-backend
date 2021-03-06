<?php

namespace Emrad\Services;

use DB;
use Emrad\Models\Card;
use Emrad\Models\Transaction;
use Emrad\Models\Wallet;
use Emrad\Repositories\Contracts\WalletRepositoryInterface;
use Emrad\User;
use Emrad\Util\CustomResponse;
use Illuminate\Support\Facades\Validator;

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

    public function fetchCards($user): CustomResponse
    {
        try {
            $wallet = $this->walletRepo->getUserWallet($user->id);
            if (!$wallet) return CustomResponse::failed('error fetching wallet');

            $cards = $wallet->cards()->get();

            return CustomResponse::success($cards);
        } catch (\Exception $e) {
            return CustomResponse::serverError();
        }
    }

    public function fetchHistory($user, $page): CustomResponse {
        try {
            $transactions = Transaction::where('user_id', $user->id)
                ->orderBy('created_at', 'DESC')
                ->paginate(
                    $page[1],
                    ['*'],
                    'page',
                    $page[0]
                );

            return CustomResponse::success($transactions);
        } catch (\Exception $e) {
            return CustomResponse::serverError();
        }
    }

    public function fetchTransaction($user, $id): CustomResponse {
        try {
            $transactions = Transaction::where([
                ['id', $id],
                ['user_id', $user->id]
            ])->first();

            if (!$transactions) return CustomResponse::badRequest('invalid transaction id');
            return CustomResponse::success($transactions);
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

    public function creditWallet($user, $data): CustomResponse {
        try {
            $validator = Validator::make($data, [
                'amount' => 'required|numeric|min:100',
                'payment_method' => 'required|in:paystack,card',
                'card_id' => 'integer|nullable'
            ]);

            if ($validator->fails()) {
                return CustomResponse::badRequest("validation failed, plaese check request");
            }

            $data['user_id'] = $user->id;
            $data['email'] = $user->email;
            $amount = $data['amount'];
            $payment_method = $data['payment_method'];
            $card_id = $data['card_id'];

            $wallet = $this->walletRepo->getUserWallet($user->id);
            if (!$wallet) return CustomResponse::failed('error fetching wallet');

            DB::beginTransaction();

            switch ($payment_method) {
                case 'card':
                    $chargeRes = $this->transactionService->chargeCard(
                        $card_id,
                        config('transactiontype.credit_wallet'),
                        $data
                    );
                    break;
                case 'paystack':
                    $chargeRes = $this->transactionService->initTransaction(
                        config('transactiontype.credit_wallet'),
                        $data
                    );
                    break;
                default:
                    return CustomResponse::badRequest('invalid payment method');
            }

            if (!$chargeRes->success) {
                DB::rollBack();
                return $chargeRes;
            }

            $charge = $chargeRes->data;
            DB::commit();
            DB::beginTransaction();

            if ($payment_method === 'paystack') return CustomResponse::success($charge);

            if ($charge->status === 'failed') {
                return CustomResponse::failed('Error initiating transaction');
            }

            if ($charge->status === 'success') {
                $charge->verified = true;
                $this->walletRepo->creditWallet($wallet, $amount);
                $charge->save();
            }

            DB::commit();
            return CustomResponse::success($charge);
        } catch (\Exception $e) {
            return CustomResponse::serverError();
        }
    }
}
