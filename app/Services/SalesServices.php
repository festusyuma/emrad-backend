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

    public function createRetailerSale() 
    {
        $retailerSale = new RetailerSale;
        $retailerSale->product_id = $request->productId;
        $retailerSale->unit_price = $request->unitPrice;
        $retailerSale->quantity = $request->quantity;
        $retailerSale->sale_amount = $request->saleAmount;
        $retailerSale->created_by = user()->id();
        $retailerSale->save();

        return $retailerSale;
    }
    


    public function makeRetailerSale($sales)
    {
        try {
            foreach ($sales as $sale) {
                $this->createRetailerSale($sale);
            }

            $updateInventory = updateInventory();

            return "Sale created successfully!";
        } catch (Exception $e) {
            return $e;
        }

    }

    /**
     * Get single retailer-sale
     *
     * @param $order_id
     */
    public function getSingleRetailerSale($sale_id)
    {
        return $this->saleRepositoryInterface->findRetailerSaleById($sale_id);
    }

    /**
     * Get all retailer sales
     *
     * @param \Collection $sale
     */
    public function getAllRetailerOrders()
    {
        return $this->orderRepositoryInterface->getAllRetailerOrders();
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
        $order = $this->orderRepositoryInterface->find($sale_id);

        $order->delete();
    }

    /**
     * Fine the requested sale by Id
     * Then Update the sale with the $request
     *
     * @param Object $request
     * @param Int|String $id
     *
     * @return \Spatie\Permission\Models\Sale
     */
    public function confirmRetailerOrder($sale_id)
    {
        try {
            $retailerOrder = RetailerSale::find($sale_id);

            $isNull = is_null($retailerOrder);

            if($isNull)
                throw new Exception("Order not found!");

            if($retailerOrder->is_confirmed == true)
                throw new Exception("Order already confirmed");

            $retailerOrder->is_confirmed = true;
            $retailerOrder->save();

            $this->updateInventory($retailerSale);

            return "Order confirmed successfully!";
        } catch (Exception $e) {
            return $e->getMessage();
        }

    }

    public function updateInventory() 
    {
        $retailerInventory = RetailerSale::firstOrNew([
            'product_id', $retailerSale->product_id
        ]);
        
        if($retailerInventory->quantity > 0) {
            $retailerInventory->quantity = $retailerInventory->quantity - $retailerSale->quantity;
            $retailerInventory->save();
        }
    }
}
