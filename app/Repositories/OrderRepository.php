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
   * Get all result from the model
   *
   * @return void
   */
    public function allByUser(){
        return $this->model
                    ->select()
                    ->orderBy('id', 'DESC')
                    ->get();
    }

    /**
   * Get paginated result from the model
   *
   * @param int $limit
   * @param array $relations
   *
   * @return void
   */
    public function paginateAllByUser($user_id, $limit, $relations = []){
        return $this->model::with($relations)->where('user_id', $user_id)->orderBy('id', 'DESC')->paginate($limit);
    }

    /**
   * Get single result from the model
   *
   * @param int $id
   * @param array $relations
   *
   * @return Model
   */
    public function findByUser($order_id, $user_id, $relations = []){

        return $this->model
                ->where('id', $order_id)
                ->where('user_id', $user_id)
                ->with($relations)
                ->firstOrFail();

    }

}
