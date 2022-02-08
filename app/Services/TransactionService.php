<?php

namespace Emrad\Services;

use Emrad\Models\Card;
use Emrad\Models\Transaction;
use Emrad\Repositories\Contracts\WalletRepositoryInterface;
use Emrad\User;
use Emrad\Util\CustomResponse;
use GuzzleHttp\Client;

class TransactionService
{
    private string $payStackUrl = 'https://api.paystack.co';
    private Client $client;
    private WalletRepositoryInterface $walletRepo;

    public function __construct(WalletRepositoryInterface $walletRepo)
    {
        $this->walletRepo = $walletRepo;
        $key = "Bearer ".env('PAYSTACK_SECRET', '');
        $this->client = new Client([
            'headers' => [
                'Authorization' => $key,
                'Content-Type' => 'application/json',
                'Cache-Control' => 'no-cache',
            ],
        ]);
    }

    public function getReference(): ?string {
        try {
            while (true) {
                $reference = generateReference();
                $transaction = Transaction::where('reference', $reference)->first();
                if (!$transaction) return $reference;
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    public function initTransaction($type, $data): CustomResponse
    {
        try {
            $reference = $this->getReference();
            if (!$reference) return CustomResponse::failed('error generating reference');
            $url = $this->payStackUrl.'/transaction/initialize';

            $body = [
                'email' => $data['email'],
                'channels' => $data['channels'],
                'amount' => $data['amount'] * 100,
                'reference' => $reference,
            ];

            $request = $this->client->post($url, [
                'json' => $body,
            ]);

            $stream = $request->getBody();
            $body = json_decode($stream->getContents());

            if (!$body->status) return CustomResponse::failed($body->message);
            else $paystackData = $body->data;

            $transaction = new Transaction([
                'type' => $type,
                'amount' => $data['amount'],
                'reference' => $paystackData->reference,
                'user_id' => $data['user_id']
            ]);
            $transaction->save();

            return CustomResponse::success([
                'transaction' => $transaction,
                'paystack_data' => $paystackData
            ]);
        } catch (\Exception $e) {
            return CustomResponse::serverError();
        }
    }

    public function confirmTransactionManual($reference): CustomResponse
    {
        try {
            $url = $this->payStackUrl.'/transaction/verify/'.$reference;
            $request = $this->client->get($url);

            $stream = $request->getBody();
            $body = json_decode($stream->getContents());

            if (!$body->status) return CustomResponse::failed($body->message);
            else $paystackData = $body->data;

            return CustomResponse::success();
        } catch (\Exception $e) {
            return CustomResponse::serverError($e);
        }
    }

    public function chargeCard(): CustomResponse
    {
        try {
            return CustomResponse::success();
        } catch (\Exception $e) {
            return CustomResponse::serverError();
        }
    }

    public function verifyTransaction($paystack_data): CustomResponse
    {
        try {
            $event = $paystack_data->event;
            $transaction = Transaction::where('reference')->first();
            if (!$transaction) return CustomResponse::badRequest('invalid request');

            if ($event != 'charge.success') {
                $transaction->status = 'failed';
                $transaction->verified = true;
                $transaction->save();

                return CustomResponse::success('transaction failed');
            }

            $transaction->status = 'success';
            $transaction->verified = true;

            switch ($event) {
                case config('transactiontype.credit_wallet'):
                    $res = $this->confirmCreditWallet($transaction->user_id, $transaction->amount);
                    break;
                case config('transactiontype.retail_order'):
                    $res = CustomResponse::success();
                    break;
                case config('transactiontype.new_card'):
                    $res = $this->confirmAddCard($transaction->user_id, $paystack_data->data);
                    break;
                default:
                    return CustomResponse::badRequest('invalid transaction');
            }

            info(json_encode($res));
            if (!$res->success) return $res;
            else $transaction->save();

            return CustomResponse::success();
        } catch (\Exception $e) {
            return CustomResponse::serverError();
        }
    }

    private function confirmAddCard($user_id, $data): CustomResponse {
        try {
            $wallet = $this->walletRepo->getUserWallet($user_id);
            if (!$wallet) return CustomResponse::failed('error fetching wallet');

            $wallet = $this->walletRepo->creditWallet($wallet, 50);
            if (!$wallet) return CustomResponse::failed('error crediting wallet');

            $authorization = $data->authorization;
            $card_data = [
                'last_4' => $authorization->last4,
                'expiration_date' => $authorization->exp_month.'/'.$authorization->exp_year,
                'full_name' => $authorization->account_name,
                'authorization_code' => $authorization->authorization_code,
                'wallet_id' => $wallet->id
            ];

            $card = new Card($card_data);
            $card->save();

            return CustomResponse::success($card);
        } catch (\Exception $e) {
            return CustomResponse::serverError();
        }
    }

    private function confirmCreditWallet($user_id, $amount): CustomResponse {
        try {
            $wallet = $this->walletRepo->getUserWallet($user_id);
            if (!$wallet) return CustomResponse::failed('error fetching wallet');

            $wallet = $this->walletRepo->creditWallet($wallet, $amount);
            if (!$wallet) return CustomResponse::failed('error crediting wallet');

            return CustomResponse::success($wallet);
        } catch (\Exception $e) {
            return CustomResponse::serverError();
        }
    }
}
