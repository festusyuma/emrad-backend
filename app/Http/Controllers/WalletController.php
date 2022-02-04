<?php

namespace Emrad\Http\Controllers;

use Emrad\Services\WalletService;

class WalletController extends Controller
{
    public $walletService;

    public function __construct(
        WalletService $walletService
    )
    {
        $this->walletService = $walletService;
    }

    public function getBalance() {
        $user = auth()->user();
        $response = $this->walletService->fetchBalance();

        return response([
            'status' => 'success',
            'message' => $response->message,
            'data' => $response->data
        ], 200);
    }

    public function addCard($user_id) {

    }

    public function confirmAddCard($user_id) {

    }

    public function creditCard($user_id) {

    }

    public function confirmCreditCard($user_id) {

    }

    public function getHistory($user_id) {

    }
}
