<?php

namespace Emrad\Services;

use Emrad\Services\InventoryServices;
use Emrad\Models\RetailerSale;
use Emrad\Models\StockHistory;
use Emrad\Models\RetailerInventory;
use Illuminate\Support\Facades\Validator;
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
        $retailerSale->amount_sold = $sale['amount_sold'];
        $fmcgSellingPrice = $this->getFmcgSellingPrice($retailerSale->product_id);
        $retailerSale->fmcg_selling_price = $fmcgSellingPrice;
        $saleAmount = $this->calculateSaleAmount($retailerSale->quantity, $retailerSale->amount_sold);
        $retailerSale->sale_amount = $saleAmount;
        $retailerSale->created_by = $user_id;
        $retailerSale->save();

        return $retailerSale;
    }

    /**
     * Calculate sale amount
     *
     * @param $quantity, $amount_sold
     */
    public function calculateSaleAmount($quantity, $amount_sold)
    {
        $saleAmount = $quantity * $amount_sold;
        return $saleAmount;
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

                $validator = Validator::make($sale, [
                    'product_id' => 'bail|required|numeric',
                    'quantity' => 'required|numeric',
                    'amount_sold' => 'required|numeric',
                ]);

                if ($validator->fails()) {
                    throw new Exception("validation failed for product: {$sale['product_id']}, transaction declined!");
                }

                $is_in_stock = $this->checkInventoryQuantity($sale);

                if(!$is_in_stock)
                    throw new Exception("Stock empty, please re-stock inventory");

                $retailerSale = $this->createRetailerSale($sale, $user_id);

                $updateInventory = $this->updateInventory($retailerSale, $user_id);

                if(!$updateInventory)
                    throw new Exception("Please check stock quantity and retry, transaction declined!");
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

    /**
     * Update the user's inventory
     *
     * @param $retailerSale
     */
    public function updateInventory($retailerSale, $user_id)
    {
        try {
            $retailerInventory = RetailerInventory::firstOrNew([
                'product_id' => $retailerSale->product_id
                ]);

            if($retailerInventory->quantity < $retailerSale->quantity)
                throw new Exception("Insufficient stock for this sale");

            $product_id = $retailerInventory->product_id;
            $currentStockBalance = $retailerInventory->quantity;
            $stockHistory = new StockHistory;

            $retailerInventory->quantity = $retailerInventory->quantity - $retailerSale->quantity;
            $retailerInventory->is_in_stock = $retailerSale->quantity == 0 ? 0 : 1;
            $retailerInventory->save();

            $newStockBalance = $retailerInventory->quantity;

            $inventory_id = $retailerInventory->id;
            $stockHistory->inventory_id = $inventory_id;
            $stockHistory->product_id = $product_id;
            $stockHistory->user_id = $user_id;
            $stockHistory->stock_balance = $currentStockBalance;
            $stockHistory->new_stock_balance = $newStockBalance;
            $stockHistory->is_depleted = true;
            $stockHistory->save();

            return true;
        } catch(Exception $e) {
            return false;
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

    /**
     * Fetch fmcg selling price
     *
     * @param $product_id
     * @return $selling_price
     */
    public function getFmcgSellingPrice($product_id)
    {
        $inventory = RetailerInventory::where('product_id', $product_id)->firstOrFail();
        $sellingPrice = $inventory->selling_price;
        return $sellingPrice;
    }


    public function updateStockHistory($retailerInventory, $retailerSale, $user_id)
    {
        try {
            $product_id = $retailerInventory->product_id;
            $inventory_id = $retailerInventory->id;
            $currentStockBalance = $retailerInventory->quantity;

            $newStockBalance = $retailerInventory->quantity - $retailerSale->quantity;

            $stockHistory = new StockHistory;
            $stockHistory->product_id = $product_id;
            $stockHistory->user_id = $user_id;
            $stockHistory->stock_balance = $currentStockBalance;
            $stockHistory->inventory_id = $inventory_id;
            $stockHistory->new_stock_balance = $newStockBalance;

            $stockHistory->save();
            return true;

        } catch(Exception $e) {
            return false;
        }
    }
}

