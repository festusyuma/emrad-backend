<?php  

namespace Emrad\Repositories\Contracts;


interface OrderRepositoryInterface extends BaseRepositoryInterface {

  /**
   * Find retailer-order by id
   *
   * @param string $order_id
   *
   * @return RetailerOrder $retailOrder
   */
  public function findRetailerOrderById($order_id, $relations = []);
  
}