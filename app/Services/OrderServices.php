<?php

namespace Emrad\Services;

use Emrad\Services\InventoryServices;
use Emrad\Models\RetailerOrder;
use Emrad\Models\RetailerInventory;
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

            $updateInventory = updateInventory();

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
    public function confirmRetailerOrder($order_id)
    {
        try {
            $retailerOrder = RetailerOrder::find($order_id);

            $isNull = is_null($retailerOrder);

            if($isNull)
                throw new Exception("Order not found!");

            if($retailerOrder->is_confirmed == true)
                throw new Exception("Order already confirmed");

            $retailerOrder->is_confirmed = true;
            $retailerOrder->save();

            $this->updateInventory($retailerOrder);

            return "Order confirmed successfully!";
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }

    public function updateInventory($retailerOrder) {

        $retailerInventory = RetailerInventory::firstOrNew([
            'product_id' => $retailerOrder->product_id
        ]);

        $retailerInventory->quantity = $retailerInventory->quantity + $retailerOrder->quantity;
        $retailerInventory->cost_price = $retailerOrder->unit_price;
        $retailerInventory->selling_price = $retailerOrder->unit_price;
        $retailerInventory->is_in_stock = $retailerOrder->quantity == 0 ?: 1;
        $retailerInventory->save();
    }
}
