<?php

namespace Emrad\Http\Controllers;

use Emrad\Services\TransactionService;
use Emrad\Util\CustomResponse;

class Webhook extends Controller
{
    public TransactionService $transactionService;

    public function __construct(
        TransactionService $transactionService
    )
    {
        $this->transactionService = $transactionService;
    }

    public function transaction($request): CustomResponse
    {
        // only a post with paystack signature header gets our attention

        $key = env('PAYSTACK_SECRET','SECRET_KEY');
        $reqHash = $request->header('x-paystack-signature');
        $hash = hash_hmac('sha512', $request->getAll, $key);

        error_log($key);
        error_log($reqHash);
        error_log($hash);
        error_log($reqHash);
        error_log(json_encode($request));

        return CustomResponse::success();
    }
}
