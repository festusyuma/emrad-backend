<?php

namespace Emrad\Http\Controllers;

use Emrad\Services\TransactionService;

class Webhook extends Controller
{
    public $transactionService;

    public function __construct(
        TransactionService $transactionService
    )
    {
        $this->transactionService = $transactionService;
    }

    public function transaction($request) {
        // todo validate key and transaction
    }
}
