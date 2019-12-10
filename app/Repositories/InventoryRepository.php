<?php  

namespace Emrad\Repositories;

use Emrad\Models\RetailerInventory;
use Emrad\Repositories\Contracts\InventoryRepositoryInterface;


class InventoryRepository extends BaseRepository implements InventoryRepositoryInterface {

    public $retailerInventory;
    
    /**
     * InventoryRepository Constructor
     * 
     * @param Emrad\Models\RetailerInventory $retailerInventory
      */
    public function __construct(RetailerInventory $retailerInventory)
    {
        $this->retailerInventory = $retailerInventory;
    }

    /**
     * Find retailer-inventory by id
     *
     * @param string $inventory_id
     *
     * @return RetailerInventory $retailInventory
     */
    public function findRetailerInventoryById($inventory_id, $relations = [])
    {
        return $this->retailerInventory
            ->where('id', $inventory_id)
            ->with($relations)
            ->first();
    }

    public function getAllRetailerInventories()
    {
        return $this->retailerInventory->all();
    }

    public function updateRetailerInventory($inventory_id)
    {
        
    }

}