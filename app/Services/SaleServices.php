<?php

namespace Emrad\Services;

use Emrad\Services\InventoryServices;
use Emrad\Models\RetailerSale;
use Emrad\Models\RetailerInventory;
use Emrad\Repositories\Contracts\SaleRepositoryInterface;
use Exception;

class SaleServices
{
    /**
     * @var $saleRepositoryInterface
     */
    public $saleRepositoryInterface;

    public function __construct(SaleRepositoryInterface $saleRepositoryInterface)
    {
        $this->saleRepositoryInterface = $saleRepositoryInterface;
    }

    /**
     * Create a new order
     *
     * @param Request $request
     *
     * @return \Emrad\Models\RetailerOrder $order
     */

    public function createRetailerSale($sale, $user_id) 
    {
        $retailerSale = new RetailerSale;
        $retailerSale->product_id = $sale['product_id'];
        $retailerSale->quantity = $sale['quantity'];
        $retailerSale->unit_price = $sale['unit_price'];
        $retailerSale->sale_amount = $retailerSale->unit_price * $retailerSale->quantity;
        $retailerSale->created_by = $user_id;
        $retailerSale->save();

        return $retailerSale;
    }
    


    public function makeRetailerSale($sales, $user_id)
    {
        try {
            foreach ($sales as $sale) {

                $is_in_stock = $this->checkInventoryQuantity($sale);

                if(!$is_in_stock)
                    throw new Exception("Stock empty, please re-stock inventory");

                $retailerSale = $this->createRetailerSale($sale, $user_id); 

                $updateInventory = $this->updateInventory($retailerSale);

                if($updateInventory)
                    throw new Exception("Please check stock quantity and retry, Inventory not updated");
            }

            return "Sale created successfully!";
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }

    /**
     * Get single retailer-sale
     *
     * @param $order_id
     */
    public function getSingleRetailerSale($sale_id)
    {
        return $this->saleRepositoryInterface->find($sale_id);
    }

    /**
     * Get all retailer sales
     *
     * @param \Collection $sale
     */
    public function getAllRetailerSales()
    {
        return $this->saleRepositoryInterface->paginate(10);
    }


    /**
     * Delete the requested sale
     *
     * @param Int|String $id
     *
     * @return void
     */
    public function delete($sale_id)
    {
        $sale = $this->orderRepositoryInterface->find($sale_id);

        $sale->delete();
    }

    public function updateInventory($retailerSale) 
    {
        try {
            $retailerInventory = RetailerInventory::firstOrNew([
                'product_id' => $retailerSale->product_id
                ]);
            
            if($retailerInventory->quantity < $retailerSale->quantity)
                throw new Exception("Insufficient stock for this sale");
            
            $retailerInventory->quantity = $retailerInventory->quantity - $retailerSale->quantity;
            $retailerInventory->is_in_stock = $retailerSale->quantity == 0 ? 0 : 1;
            $retailerInventory->save();

        } catch(Exception $e) {
            return true;
        }
    }

    /**
     * Check inventory quantity
     * @return bool
     */
    public function checkInventoryQuantity($sale)
    {
        $inventory = RetailerInventory::where('product_id', $sale['product_id'])->firstOrFail();
        return $inventory->is_in_stock;
    }
}
