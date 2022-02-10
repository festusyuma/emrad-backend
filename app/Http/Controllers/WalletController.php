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

    public function getCards() {
        $user = auth()->user();
        $response = $this->walletService->fetchaCards($user);

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

    public function getHistory(Request $request) {
        $user = auth()->user();
        $page = $request->get('page', 1);
        $size = $request->get('size', 5);

        $response = $this->walletService->fetchHistory($user, [$page, $size]);

        return response([
            'status' => $response->success,
            'message' => $response->message,
            'data' => $response->data
        ], 200);
    }

    public function getTransactionStatus($id) {
        $user = auth()->user();
        $response = $this->walletService->fetchTransaction($user, $id);

        return response([
            'status' => $response->success,
            'message' => $response->message,
            'data' => $response->data
        ], 200);
    }
}
