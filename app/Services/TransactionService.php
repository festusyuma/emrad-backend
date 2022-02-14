<?php

namespace Emrad\Services;

use Emrad\Models\Card;
use Emrad\Models\Transaction;
use Emrad\Repositories\Contracts\WalletRepositoryInterface;
use Emrad\User;
use Emrad\Util\CustomResponse;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

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
            'http_errors' => false
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
            $transaction = $this->createTransaction($data['user_id'], $type, $data['amount']);
            if (!$transaction) return CustomResponse::failed('error generating transaction');
            $url = $this->payStackUrl.'/transaction/initialize';

            $body = [
                'email' => $data['email'],
                'channels' => $data['channels'] ?: [],
                'amount' => $data['amount'] * 100,
                'reference' => $transaction->reference,
            ];

            $paystackData = $this->fetchPaystackData($url, 'POST', $body);
            $transaction->save();

            return CustomResponse::success([
                'transaction' => $transaction,
                'paystack_data' => $paystackData
            ]);
        } catch (\Exception $e) {
            return CustomResponse::serverError();
        }
    }

    public function chargeCard($id, $type, $data): CustomResponse
    {
        try {
            $wallet = $this->walletRepo->getUserWallet($data['user_id']);
            if (!$wallet) return CustomResponse::failed('error fetching wallet');

            $card = Card::where([
                ['id', $id],
                ['wallet_id', $wallet->id]
            ])->first();
            if (!$card) return CustomResponse::badRequest('invalid card id');

            $transaction = $this->createTransaction($data['user_id'], $type, $data['amount']);
            if (!$transaction) return CustomResponse::failed('error generating transaction');
            $url = $this->payStackUrl.'/transaction/charge_authorization';

            $body = [
                'email' => $data['email'],
                'channels' => $data['channels'] || [],
                'amount' => $data['amount'] * 100,
                'reference' => $transaction->reference,
                'authorization_code' => $card->authorization_code
            ];

            $transaction->card_id = $id;
            $paystackData = $this->fetchPaystackData($url, 'POST', $body);

            if ($paystackData->status !== 'success') {
                $transaction->status = 'failed';
                $transaction->verified = true;
                $transaction->save();
                return CustomResponse::failed($paystackData->message);
            }

            $transaction->status = 'success';
            $transaction->save();

            return CustomResponse::success($transaction);
        } catch (\Exception $e) {
            return CustomResponse::serverError();
        }
    }

    public function chargeWallet($type, $data): CustomResponse
    {
        try {
            $transaction = $this->createTransaction($data['user_id'], $type, $data['amount']);
            if (!$transaction) return CustomResponse::failed('error generating transaction');

            $wallet = $this->walletRepo->getUserWallet($data['user_id']);
            if (!$wallet) return CustomResponse::failed('error fetching wallet');

            $amount = (double) $data['amount'];
            if ($amount > $wallet->balance) {
                $transaction->status = 'failed';
                $transaction->verified = true;
                $transaction->save();
                return CustomResponse::failed('insufficient funds');
            }

            $wallet->balance -= $amount;
            $wallet->save();

            $transaction->status = 'success';
            $transaction->verified = true;

            $transaction->save();

            return CustomResponse::success($transaction);
        } catch (\Exception $e) {
            return CustomResponse::serverError();
        }
    }

    private function createTransaction($user_id, $type, $amount): ?Transaction
    {
        try {
            $reference = $this->getReference();
            if (!$reference) return null;

            return new Transaction([
                'type' => $type,
                'amount' => $amount,
                'reference' => $reference,
                'user_id' => $user_id
            ]);
        } catch (\Exception $e) {
            return null;
        }
    }

    private function fetchPaystackData($url, $method = 'GET', $body = null)
    {
        try {
            switch ($method) {
                case 'POST':
                    $request = $this->client->post($url, ['json' => $body]);
                    break;
                case 'GET':
                    $request = $this->client->get($url);
                    break;
                default:
                    return null;
            }

            $stream = $request->getBody();
            $body = json_decode($stream->getContents());

            if (!$body->status) return null;
            else $paystackData = $body->data;

            return $paystackData;
        } catch (\Exception $e) {
            return null;
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

    public function verifyTransaction($paystack_data): CustomResponse
    {
        try {
            $event = $paystack_data['event'];
            $data = $paystack_data['data'];

            $transaction = Transaction::where('reference', $data['reference'])->first();
            if (!$transaction) return CustomResponse::badRequest('invalid transaction');
            if ($transaction->verified) return CustomResponse::failed('invalid transaction');

            if ($event != 'charge.success') {
                $transaction->status = 'failed';
                $transaction->verified = true;
                $transaction->save();

                return CustomResponse::success('transaction failed');
            }

            $transaction->status = 'success';
            $transaction->verified = true;

            switch ($transaction->type) {
                case config('transactiontype.credit_wallet'):
                    $res = $this->confirmCreditWallet($transaction->user_id, $transaction->amount);
                    break;
                case config('transactiontype.retail_order'):
                    $res = CustomResponse::success();
                    break;
                case config('transactiontype.new_card'):
                    $res = $this->confirmAddCard($transaction->user_id, $data);
                    break;
                default:
                    return CustomResponse::badRequest('invalid transaction');
            }

            info(json_encode($res));
            if (!$res->success) return $res;
            else $transaction->save();

            return CustomResponse::success();
        } catch (\Exception $e) {
            return CustomResponse::serverError($e);
        }
    }

    private function confirmAddCard($user_id, $data): CustomResponse {
        try {
            $wallet = $this->walletRepo->getUserWallet($user_id);
            if (!$wallet) return CustomResponse::failed('error fetching wallet');

            $wallet = $this->walletRepo->creditWallet($wallet, 50);
            if (!$wallet) return CustomResponse::failed('error crediting wallet');

            $authorization = $data['authorization'];
            if (!$authorization['authorization_code'] || $authorization['authorization_code'] == '') return $authorization['error saving card'];

            $card_data = [
                'last_4' => $authorization['last4'],
                'expiration_date' => $authorization['exp_month'].'/'.$authorization['exp_year'],
                'full_name' => $authorization['account_name'] ?? '',
                'authorization_code' => $authorization['authorization_code'],
                'wallet_id' => $wallet->id
            ];

            $card = Card::where([
                ['last_4', $authorization['last4']],
                ['expiration_date', $authorization['exp_month'].'/'.$authorization['exp_year']],
                ['wallet_id', $wallet->id]
            ])->first();
            if (!$card) $card = new Card($card_data);
            else $card->authorization_code = $authorization['authorization_code'];

            $card->save();

            return CustomResponse::success($card);
        } catch (\Exception $e) {
            return CustomResponse::serverError($e);
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
