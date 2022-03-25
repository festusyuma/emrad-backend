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

    public function getStats(Request $request) {
        $result = $this->orderServices->fetchStats();

        return response([
            'status' => $result->success,
            'message' => $result->message,
            'data' => $result->data
        ], $result->status);
    }

    public function getOrders(Request $request) {
        $limit = $request->get('size', 10);
        $filters = [];
        $status = strtolower($request->get('status', 'all'));
        $customerId = $request->get('customer_id', null);

        if (strtolower($status) && $status != 'all') {
            if ($status === 'confirmed') $filters[] = ['confirmed', true];
            if ($status === 'pending') $filters[] = ['confirmed', false];
        }

        $result = $this->orderServices->fetchOrders($limit, $filters);

        return response([
            'status' => $result->success,
            'message' => $result->message,
            'data' => $result->data
        ], $result->status);
    }

    public function getOrdersByCustomer(Request $request, $id) {
        $limit = $request->get('size', 10);
        $filters = [];

        $result = $this->orderServices->fetchCustomerOrders($id, $limit, $filters);

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

    public function getTopRetailers(Request $request) {
        $filters = [];
        $result = $this->orderServices->fetchTopRetailers($filters);

        return response([
            'status' => $result->success,
            'message' => $result->message,
            'data' => $result->data
        ], $result->status);
    }
}
