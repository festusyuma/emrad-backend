<?php

namespace Emrad\Http\Controllers\Distributor;

use Emrad\Http\Controllers\Controller;
use Emrad\Services\Distributor\OrdersServices;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    private OrdersServices $orderServices;

    public function __construct(OrdersServices $orderServices)
    {
        $this->orderServices = $orderServices;
    }

    public function getOrders(Request $request) {
        $limit = $request->get('size', 10);
        $result = $this->orderServices->fetchOrders($limit);

        return response([
            'status' => $result->success,
            'message' => $result->message,
            'data' => $result->data
        ], $result->status);
    }

    public function fulfillOrder(Request $request, $orderId) {
        $result = $this->orderServices->fulfillOrder($orderId);

        return response([
            'status' => $result->success,
            'message' => $result->message,
            'data' => $result->data
        ], $result->status);
    }
}
