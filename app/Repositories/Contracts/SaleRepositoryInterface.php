<?php  

namespace Emrad\Repositories\Contracts;


interface SaleRepositoryInterface extends BaseRepositoryInterface {

  /**
   * Find retailer-sale by id
   *
   * @param string $sale_id
   *
   * @return RetailerSale $retailSale
   */
  // public function findRetailerSaleById($sale_id, $relations = []);
  
}