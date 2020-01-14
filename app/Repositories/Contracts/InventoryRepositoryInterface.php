<?php  

namespace Emrad\Repositories\Contracts;


interface InventoryRepositoryInterface extends BaseRepositoryInterface {

  /**
   * Find retailer-inventory by id
   *
   * @param string $inventory_id
   *
   * @return RetailerInventory $retailInventory
   */
  // public function findRetailerInventoryById($inventory_id, $relations = []);
  
}