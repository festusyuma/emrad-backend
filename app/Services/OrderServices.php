<?php
namespace Emrad\Services;

use Emrad\Models\RetailerOrder;
use Emrad\Repositories\Contracts\OrderRepositoryInterface;

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
    public function createOrder($request)
    {
        $retailerOrder = new RetailerOrder;
        $retailerOrder->name = $request->name;
        $retailerOrder->guard_name = 'api';
        $retailerOrder->save();

        return $retailerOrder;
    }

    /**
     * Get all orders
     *
     * @param \Collection $order
     */
    public function getOrders()
    {
        return $this->orderRepositoryInterface->all();
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
