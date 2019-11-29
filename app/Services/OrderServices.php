<?php

namespace Emrad\Services;

use Emrad\Models\RetailerOrder;
use Emrad\Repositories\Contracts\OrderRepositoryInterface;
use Exception;

class OrderServices
{
    /**
     * @var $orderRepositoryInterface
     */
    public $orderRepositoryInterface;

    public function __construct(OrderRepositoryInterface $orderRepositoryInterface)
    {
        $this->orderRepositoryInterface = $orderRepositoryInterface;
    }

    /**
     * Create a new order
     *
     * @param Request $request
     *
     * @return \Emrad\Models\RetailerOrder $order
     */
    public function makeRetailerOrder($orders)
    {
        try {
            foreach ($orders as $order) {
                $retailerOrder = RetailerOrder::create([
                    'product_id' => $order['product_id'],
                    'company_id' => $order['company_id'],
                    'quantity' => $order['quantity'],
                    'unit_price' => $order['unit_price'],
                    'order_amount' => $order['quantity'] * $order['unit_price'],
                    'created_by' => $order['created_by']
                ]);
            }
            return "Order created successfully!";
        } catch (Exception $e) {
            return $e;
        }

        
    }

    /**
     * Get single retailer-order
     *
     * @param $order_id
     */
    public function getSingleRetailerOrder($order_id)
    {
        return $this->orderRepositoryInterface->findRetailerOrderById($order_id);        
    }

    /**
     * Get all retailer orders
     *
     * @param \Collection $order
     */
    public function getAllRetailerOrders()
    {
        return $this->orderRepositoryInterface->getAllRetailerOrders();
    }


    /**
     * Delete the requested order
     *
     * @param Int|String $id
     *
     * @return void
     */
    public function delete($order_id)
    {
        $order = $this->orderRepositoryInterface->find($order_id);

        $order->delete();
    }

    /**
     * Fine the requested order by Id
     * Then Update the order with the $request
     *
     * @param Object $request
     * @param Int|String $id
     *
     * @return \Spatie\Permission\Models\Order
     */
    public function updateOrder($request, $order)
    {
        $order->name = $request->name;
        $order->guard_name = 'api';
        $order->save();

        return $order;

    }
}
