<?php

namespace Emrad\Services;

use Cassandra\Custom;
use Emrad\Models\Transaction;
use Emrad\Util\CustomResponse;
use GuzzleHttp\Client;

class TransactionService
{
    private string $payStackUrl = 'https://api.paystack.co';
    private Client $client;

    public function __construct()
    {
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

    public function initTransaction($data): CustomResponse
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

            return CustomResponse::success($paystackData);
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

    public function verifyTransaction($data): CustomResponse
    {
        try {
            return CustomResponse::success();
        } catch (\Exception $e) {
            return CustomResponse::serverError();
        }
    }
}
