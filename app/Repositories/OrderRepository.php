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
        $this->model = $retailerOrder;
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
        return $this->model
            ->where('id', $order_id)
            ->with($relations)
            ->firstOrFail();
    }

    public function getAllRetailerOrders()
    {
        return $this->model->all();
    }

}
