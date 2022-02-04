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


    /**
     * Display a listing of the retailer-order resource collection.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllRetailerOrders()
    {
        $retailerOrders = $this->orderServices->getAllRetailerOrders(auth()->id(), 10);
        return new RetailerOrderCollection($retailerOrders);
    }


    /**
     * Display the specified resource.
     *
     * @param  \Emrad\Models\RetailerOrder  $product
     * @return \Illuminate\Http\Response
     */
    public function getSingleRetailerOrder($order_id)
    {
        $retailerOrder = $this->orderServices->getSingleRetailerOrder($order_id, auth()->id());

        return response([
            'status' => 'success',
            'message' => 'Order retrieved succesfully',
            'data' => new RetailerOrderResource($retailerOrder)
        ], 200);
    }

    /**
     * @throws Exception
     */
    public function makeRetailerOrder(MakeRetailerOrderRequest $request)
    {
        $orders = $request->orders;
        $result = $this->orderServices->makeRetailerOrder($orders, auth()->id());

        return response([
            'status' => $result->success ? 'success' : 'failed',
            'message' => $result->message,
            'data' => $result->data
        ], $result->status);
    }

    public function getStockBalance($product_id)
    {
        $stockBalance = $this->orderServices->getStockBalance($product_id);
        return response([
            'status' => 'success',
            'isInInventory' => $stockBalance[0],
            'stockBalance' => $stockBalance[1]
        ], 200);
    }

    public function confirmRetailerOrder($order_id)
    {
        $result = $this->orderServices->confirmRetailerOrder($order_id, auth()->id());

        return response([
            'status' => 'success',
            'message' => $result
        ], 200);
    }
}
