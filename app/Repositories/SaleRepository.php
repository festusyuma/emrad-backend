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
     * Find retailer-sale by id
     *
     * @param string $sale_id
     *
     * @return RetailerSale $retailSale
     */
    public function findRetailerSaleById($sale_id, $relations = [])
    {
        return $this->model
            ->where('id', $sale_id)
            ->with($relations)
            ->firstOrFail();
    }

    public function getAllRetailerSales()
    {
        return $this->model->all();
    }

}
