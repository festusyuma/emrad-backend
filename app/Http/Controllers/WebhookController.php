<?php

namespace Emrad\Http\Controllers;

use Emrad\Services\TransactionService;
use Emrad\Util\CustomResponse;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public TransactionService $transactionService;

    public function __construct(
        TransactionService $transactionService
    )
    {
        $this->transactionService = $transactionService;
    }

    public function transaction(Request $request)
    {
        info('webhook called');

        $key = env('PAYSTACK_SECRET','SECRET_KEY');
        info($key);

        $reqHash = $request->header('x-paystack-signature', '');
        info($reqHash);

        $encodedBody = $request->getContent();
        info($encodedBody);

        $hash = hash_hmac('sha512', $encodedBody, $key);
        info($hash);


        if ($reqHash !== $hash) {
            return response([
                'status' => false,
                'message' => 'invalid hash',
                'data' => null
            ], 400);
        }

        $response = $this->transactionService->verifyTransaction($request->json()->all());

        return response([
            'status' => $response->success,
            'message' => $response->message,
            'data' => $response->data
        ], 200);
    }
}