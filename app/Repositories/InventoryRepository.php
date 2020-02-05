<?php

namespace Emrad\Repositories;

use Emrad\Models\RetailerInventory;
use Emrad\Repositories\Contracts\InventoryRepositoryInterface;


class InventoryRepository extends BaseRepository implements InventoryRepositoryInterface {

    public $model;

    /**
     * InventoryRepository Constructor
     *
     * @param Emrad\Models\RetailerInventory $retailerInventory
      */
    public function __construct(RetailerInventory $retailerInventory)
    {
        $this->model = $retailerInventory;
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
    public function findByUser($inventory_id, $user_id, $relations = []){

        return $this->model
                ->where('id', $inventory_id)
                ->where('user_id', $user_id)
                ->with($relations)
                ->firstOrFail();

    }
}
