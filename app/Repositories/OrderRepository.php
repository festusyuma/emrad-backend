<?php  

namespace Emrad\Repositories;

use Emrad\Models\RetailerOrder;
use Emrad\Repositories\Contracts\OrderRepositoryInterface;


class OrderRepository extends BaseRepository implements OrderRepositoryInterface {

    public $retailerOrder;
    
    /**
     * OrderRepository Constructor
     * 
     * @param Emrad\Models\RetailerOrder $retailerOrder
      */
    public function __construct(RetailerOrder $retailerOrder)
    {
        $this->retailerOrder = $retailerOrder;
    }

    /**
     * Find retailer-order by id
     *
     * @param string $order_id
     *
     * @return RetailerOrder $retailOrder
     */
    public function findRetailerOrderById($order_id, $relations = [])
    {
        return $this->retailerOrder
            ->where('id', $order_id)
            ->with($relations)
            ->first();
    }

    public function getAllRetailerOrders()
    {
        return $this->retailerOrder->all();
    }

}