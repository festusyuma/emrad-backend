<?php

namespace Emrad\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Emrad\Http\Requests\MakeRetailerOrderRequest;
use Emrad\Http\Resources\RetailerOrderCollection;
use Emrad\Http\Resources\RetailerOrderResource;
use Emrad\Models\RetailerOrder;
use Emrad\Services\OrderServices;


class RetailerOrderController extends Controller
{

    /**
     * @var OrderServices $orderServices
     */
    public $orderServices;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(OrderServices $orderServices)
    {
        $this->orderServices = $orderServices;
    }


    public function getAllRetailerOrders()
    {
        $result = $this->orderServices->getAllRetailerOrders(auth()->id(), 10);

        return response([
            'status' => $result->success,
            'message' => $result->message,
            'data' => $result->data
        ], $result->status);
    }

    public function totalOrderPayment()
    {
        $result = $this->orderServices->totalOrderPayment(auth()->id());

        return response([
            'status' => $result->success,
            'message' => $result->message,
            'data' => $result->data
        ], $result->status);
    }



    public function getSingleRetailerOrder($order_id)
    {
        $result = $this->orderServices->getSingleRetailerOrder($order_id, auth()->id());

        return response([
            'status' => $result->success,
            'message' => $result->message,
            'data' => $result->data
        ], $result->status);
    }

    /**
     * @throws Exception
     */
    public function makeRetailerOrder(MakeRetailerOrderRequest $request)
    {
        $orderReq = [
            'items' => $request->get('orders'),
            'payment_method' => $request->get('payment_method'),
            'card_id' => $request->get('card_id')
        ];

        $result = $this->orderServices->makeRetailerOrder($orderReq, auth()->user());

        return response([
            'status' => $result->success,
            'message' => $result->message,
            'data' => $result->data
        ], $result->status);
    }

    public function getStockBalance($product_id)
    {
        $result = $this->orderServices->getStockBalance($product_id);

        return response([
            'status' => $result->success,
            'message' => $result->message,
            'data' => $result->data
        ], $result->status);
    }

    public function confirmRetailerOrder($item_id)
    {
        $result = $this->orderServices->confirmRetailerOrder($item_id, auth()->id());

        return response([
            'status' => $result->success,
            'message' => $result->message,
            'data' => $result->data
        ], $result->status);
    }
}
