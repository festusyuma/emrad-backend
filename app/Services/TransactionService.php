<?php

namespace Emrad\Services;

use Emrad\Util\CustomResponse;
use GuzzleHttp\Client;

class TransactionService
{
    private $payStackUrl = 'https://api.paystack.co';
    private $client;

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

    public function initTransaction($data): CustomResponse
    {
        try {
            $reference = generateReference();
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

            return CustomResponse::success($body);
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
