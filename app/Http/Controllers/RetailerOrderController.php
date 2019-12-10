<?php

namespace Emrad\Http\Controllers;

use Illuminate\Http\Request;
use Emrad\Http\Requests\MakeRetailerOrder;
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
        
        $retailerOrders = $this->orderServices->getAllRetailerOrders();

        return response([
            'status' => 'success',
            'data' => new RetailerOrderCollection($retailerOrders)
        ], 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  \Emrad\Models\RetailerOrder  $product
     * @return \Illuminate\Http\Response
     */
    public function getSingleRetailerOrder($order_id)
    {
        $retailerOrder = $this->orderServices->getSingleRetailerOrder($order_id);

        return response([
            'status' => 'success',
            'data' => new RetailerOrderResource($retailerOrder)
        ], 200);
        
    }


    /**
     * Create new multiple retailer-orders in database.
     *
     * @param MakeRetailOrder $request
     */
    public function makeRetailerOrder(MakeRetailerOrder $request)
    {
        $orders = $request->orders;

        $result = $this->orderServices->makeRetailerOrder($orders);

        return response([
            'status' => 'success',
            'message' => $result
        ], 200);
    }
}
