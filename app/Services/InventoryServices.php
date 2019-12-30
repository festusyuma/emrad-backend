<?php

namespace Emrad\Services;

use Emrad\Models\RetailerInventory;
use Emrad\Repositories\Contracts\InventoryRepositoryInterface;
use Exception;

class InventoryServices
{
    /**
     * @var $inventoryRepositoryInterface
     */
    public $inventoryRepositoryInterface;

    public function __construct(InventoryRepositoryInterface $inventoryRepositoryInterface)
    {
        $this->inventoryRepositoryInterface = $inventoryRepositoryInterface;
    }

    /**
     * Create a new inventory
     *
     * @param Request $request
     *
     * @return \Emrad\Models\RetailerInventory $inventory
     */
    public function makeRetailerInventory($inventories)
    {
        try {
            foreach ($inventories as $inventory) {
                $retailerInventory = RetailerInventory::create([
                    'product_id' => $inventory['product_id'],
                    'company_id' => $inventory['company_id'],
                    'quantity' => $inventory['quantity'],
                    'unit_price' => $inventory['unit_price'],
                    'inventory_amount' => $inventory['quantity'] * $inventory['unit_price'],
                    'created_by' => $inventory['created_by']
                ]);
            }
            return "Inventory created successfully!";
        } catch (Exception $e) {
            return $e;
        }


    }

    /**
     * Get single retailer-inventory
     *
     * @param int $inventory_id
     */
    public function getSingleRetailerInventory(int $inventory_id)
    {
        return $this->inventoryRepositoryInterface->findRetailerInventoryById($inventory_id);
    }

    /**
     * Get all retailer inventories
     *
     * @param \Collection $inventory
     */
    public function getAllRetailerInventories()
    {
        return $this->inventoryRepositoryInterface->getAllRetailerInventories();
    }

    /**
     * Find the requested inventory by Id
     * Then Update the inventory with the $request
     *
     * @param Object $request
     * @param Int|String $id
     *
     * @return \Spatie\Permission\Models\Inventory
     */
    public function updateRetailerInventory(int $inventory_id, $inventorySellingPrice)
    {
        try {
            $inventory = $this->inventoryRepositoryInterface->findRetailerInventoryById($inventory_id);

            $inventory->selling_price = $inventorySellingPrice;

            $inventory->save();

            return "Inventory updated successfully!";
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }
}
