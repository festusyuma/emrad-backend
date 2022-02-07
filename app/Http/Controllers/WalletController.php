<?php

namespace Emrad\Http\Controllers;

use Emrad\Services\WalletService;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public WalletService $walletService;

    public function __construct(
        WalletService $walletService
    )
    {
        $this->walletService = $walletService;
    }

    public function getBalance() {
        $user = auth()->user();
        $response = $this->walletService->fetchBalance($user);

        return response([
            'status' => $response->success,
            'message' => $response->message,
            'data' => $response->data
        ], 200);
    }

    public function addCard() {
        $user = auth()->user();
        $response = $this->walletService->addCard($user);

        return response([
            'status' => $response->success,
            'message' => $response->message,
            'data' => $response->data
        ], 200);
    }

    public function confirmAddCard($user_id) {

    }

    public function creditCard(Request $request) {
        $request->validate([
            'amount' => 'required',
            'source' => 'required'
        ]);

        $user = auth()->user();
        $response = $this->walletService->creditWallet($user);

        return response([
            'status' => $response->success,
            'message' => $response->message,
            'data' => $response->data
        ], 200);
    }

    public function confirmCreditCard($user_id) {

    }

    public function getHistory($user_id) {

    }
}
