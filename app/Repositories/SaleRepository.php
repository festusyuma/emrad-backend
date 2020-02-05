<?php

namespace Emrad\Repositories;

use Emrad\Models\RetailerSale;
use Emrad\Repositories\Contracts\SaleRepositoryInterface;


class SaleRepository extends BaseRepository implements SaleRepositoryInterface {

    public $retailerSale;

    /**
     * SaleRepository Constructor
     *
     * @param Emrad\Models\RetailerSale $retailerSale
      */
    public function __construct(RetailerSale $retailerSale)
    {
        $this->model = $retailerSale;
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
    public function findByUser($sale_id, $user_id, $relations = []){

        return $this->model
                ->where('id', $sale_id)
                ->where('user_id', $user_id)
                ->with($relations)
                ->firstOrFail();

    }
}
