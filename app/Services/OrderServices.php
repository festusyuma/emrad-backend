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


    public function getSingleRetailerOrder($order_id)
    {
        return $this->orderRepositoryInterface->findRetailerOrderById($order_id);        
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

    /**
     * Get all orders
     *
     * @param \Collection $orderName
     */
    public function findByName($orderName)
    {
        return $this->orderRepositoryInterface->findByName($orderName);
    }

    /**
     * Delete the requested order
     *
     * @param Int|String $id
     *
     * @return void
     */
    public function delete($id)
    {
        $order = $this->orderRepositoryInterface->find($id);

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

    /**
     * AttachPermissions
     * @param \Spatie\Permission\Models\Order $order
     * @param Array $permissions
     *
     * @return
     */
    public function attachPermissions($order,  $permissions)
    {
        return $order->syncPermissions($permissions);
    }

    /**
     * Get array of the specified order permissions
     *
     * @param \Spatie\Permission\Models\Order $order
     *
     * @return Array $permissionsId
     */
    public function getActivePermissions($order)
    {
        $collection = collect($order->permissions);

        $plucked = $collection->pluck('id');

        return $plucked->all();
    }
}
