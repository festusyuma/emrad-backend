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

    public function createRetailerOrder($order) 
    {
        $retailerOrder = new RetailerOrder;
        $retailerOrder->product_id = $order['product_id'];
        $retailerOrder->company_id = $order['company_id'];
        $retailerOrder->unit_price = $order['quantity'];
        $retailerOrder->order_amount = $order['unit_price'];
        $retailerOrder->created_by = 1;
        $retailerOrder->save();

        return $retailerOrder;
    }
    


    public function makeRetailerOrder($orders)
    {
        try {
            foreach ($orders as $order) {
                $retailerOrder = $this->createRetailerOrder($order);
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

            return "Order confirmed successfully!";
            
            $this->updateInventory($retailerOrder);
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }

    public function updateInventory($retailerOrder) 
    {
        $retailerInventory = RetailerInventory::firstOrCreate([
         'product_id'   , $retailerOrder->product_id
        ], [
            "quantity" => $retailerInventory->quantity + $retailerOrder->quantity,
            "cost_price" => $retailerOrder->unit_price,
            "selling_price" => $retailerOrder->unit_price,
            "is_in_stock" => $retailerOrder->quantity == 0 ?: 1
        ]);
            // $retailerInventory->quantity = $retailerInventory->quantity + $retailerOrder->quantity;
            // $retailerInventory->cost_price = $retailerOrder->unit_price;
            // $retailerInventory->selling_price = $retailerOrder->unit_price;
            // $retailerInventory->is_in_stock = $retailerOrder->quantity == 0 ?: 1;
            // $retailerInventory->save();
    }
}
