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
                $this->createOrder($order);
            }
            return "Order created successfully!";
        } catch (Exception $e) {
            return $e;
        }


    }

    /**
     * create order from the payload
     *
     * @param array $order
     *
     * @return null
     */
    public function createOrder(array $order)
    {
        RetailerOrder::create([
            'product_id' => $order['product_id'],
            'company_id' => $order['company_id'],
            'quantity' => $order['quantity'],
            'unit_price' => $order['unit_price'],
            'order_amount' => $order['quantity'] * $order['unit_price'],
            'created_by' => $order['created_by']
        ]);
    }


    public function getSingleRetailerOrder($order_id)
    {
        $test =  $this->orderRepositoryInterface->find($order_id);
        dd($test);
    }

    /**
     * Get all orders
     *
     * @param \Collection $order
     */
    public function getAllRetailerOrders()
    {
        return $this->orderRepositoryInterface->getAllRetailerOrders();
    }
}
