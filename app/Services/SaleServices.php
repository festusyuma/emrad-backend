<?php

namespace Emrad\Services;

use Emrad\Services\InventoryServices;
use Emrad\Models\RetailerSale;
use Emrad\Models\RetailerInventory;
use Emrad\Repositories\Contracts\SaleRepositoryInterface;
use Exception;
use DB;


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
     * @param Request $sale
     *
     * @return \Emrad\Models\RetailerSale $sale
     */

    public function createRetailerSale($sale, $user_id)
    {
        $retailerSale = new RetailerSale;
        $retailerSale->product_id = $sale['product_id'];
        $retailerSale->quantity = $sale['quantity'];
        $retailerSale->unit_price = $sale['unit_price'];
        $retailerSale->discount = $sale['discount'];
        $totalAmount = $this->calculateTotalAmount($retailerSale->quantity, $retailerSale->unit_price);
        $retailerSale->total_amount = $totalAmount;
        $saleAmount = $this->calculateSaleAmount($retailerSale->quantity, $retailerSale->unit_price, $retailerSale->discount);
        $retailerSale->sale_amount = $saleAmount;
        $retailerSale->created_by = $user_id;
        $retailerSale->save();

        return $retailerSale;
    }

    /**
     * Calculate sale amount
     *
     * @param $quantity, $unit_price, $discount
     */
    public function calculateSaleAmount($quantity, $unit_price, $discount)
    {
        $total = $quantity * $unit_price;
        $discountAmount = $total * $discount / 100;
        $saleAmount = $total - $discountAmount;
        return $saleAmount;
    }

    /**
     * Calculate total amount
     *
     * @param $quantity, $unit_price
     */
    public function calculateTotalAmount($quantity, $unit_price)
    {
        $totalAmount = $quantity * $unit_price;
        return $totalAmount;
    }

    /**
     * Create sale records and update inventory quantity
     *
     * @param Int|String $user_id, $sales
     */
    public function makeRetailerSale($sales, $user_id)
    {
        DB::beginTransaction();
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
            DB::commit();
            return "Sale created successfully!";
        } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }

    }

    /**
     * Get single retailer-sale
     *
     * @param Int|String $sale_id
     */
    public function getSingleRetailerSale($sale_id)
    {
        return $this->saleRepositoryInterface->find($sale_id);
    }

    /**
     * Get all retailer sales
     *
     * @return \Collection $sales
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
        $sale = $this->saleRepositoryInterface->find($sale_id);

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
