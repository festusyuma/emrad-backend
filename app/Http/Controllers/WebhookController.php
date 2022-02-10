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
        $reqHash = $request->header('x-paystack-signature', '');
        $encodedBody = $request->getContent();

        try {
            $hash = hash_hmac('sha512', $encodedBody, $key);
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $hash = '';
        }

        info($reqHash);
        info($hash);
        info($encodedBody);

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
