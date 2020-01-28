<?php

namespace Emrad\Services;

use Emrad\Models\RetailerInventory;
use Emrad\Models\Product;
use Emrad\Repositories\Contracts\InventoryRepositoryInterface;
use Illuminate\Support\Facades\Validator;
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
                $validator = Validator::make($inventory, [
                    'product_id' => 'bail|required|numeric',
                    'company_id' => 'nullable',
                    'quantity' => 'required|numeric',
                ]);

                if ($validator->fails()) {
                    throw new Exception("validation failed, please check request");
                }

                $product = Product::find($inventory['product_id']);

                $retailerInventory = RetailerInventory::create([
                    'product_id' => $product->id,
                    'company_id' => $inventory['company_id'],
                    'quantity' => $inventory['quantity'],
                    'unit_price' => $product->price,
                    'selling_price' => $product->selling_price,
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
        return $this->inventoryRepositoryInterface->find($inventory_id);
    }

    /**
     * Get all retailer inventories
     *
     * @param \Collection $inventory
     */
    public function getAllRetailerInventories()
    {
        return $this->inventoryRepositoryInterface->paginate(10);
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
