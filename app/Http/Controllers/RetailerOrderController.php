<?php

namespace Emrad\Http\Controllers;

use Illuminate\Http\Request;
use Emrad\Http\Requests\MakeRetailerOrder;
use Emrad\Http\Resources\RetailerOrderCollection;
use Emrad\Models\RetailerOrder;

class RetailerOrderController extends Controller
{
    /**
     * @var UsersServices $usersServices
     */
    public $usersServices;

    /**
     * @var RolesServices $rolesServices
     */
    public $rolesServices;

    /**
     * @var PermissionsServices $permissionsServices
     */
    public $permissionsServices;

    /**
     * @var OrderServices $orderServices
     */
    public $orderServices;
    
    /**
     * @var RetailerOrder $retailerOrder
     */
    private $retailerOrder;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        UsersServices $usersServices,
        RolesServices $rolesServices,
        PermissionsServices $permissionsServices
    ) {
        $this->usersServices = $usersServices;
        $this->permissionsServices = $permissionsServices;
        $this->rolesServices = $rolesServices;
    }

    public function setOrder($order_id)
    {
        $this->order = Order::find($order_id);
    }

    public function setUser($user_id)
    {
        $this->user = User::find($user_id);
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Display a listing of the retailer-order resource collection.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllRetailerOrder()
    {
        $retailerOrders = RetailerOrder::all();
        return new RetailerOrderCollection($retailerOrders);
    }

    public function makeOrder(MakeRetailerOrder $request)
    {

        $orders = request()->all();

        foreach ($orders as $order) {
            $retailerOrder = RetailerOrder::create([
                'product_id' => $order->product_id,
                'company_id' => $order->company_id,
                'quantity' => $order->quantity,
                'unit_price' => $order->unit_price,
                'order_amount' => $order->quantity * $order->unit_price,
                'created_by' => $order->created_by
            ]);
        }
    }

    public function order(Request $request)
    {
        $validator = Validator::make(request()->all(), Rules::get('POST_ORDER'));

        if ($validator->fails()) {
            return $this->validationErrors($validator->getMessageBag()->all());
        }
        try {
            $order = $this->orderRepository->order($request);
            return $this->withData($order);
        } catch (Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
