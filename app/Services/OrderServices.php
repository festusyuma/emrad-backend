<?php

namespace Emrad\Services;

use Emrad\Services\InventoryServices;
use Emrad\Models\Product;
use Emrad\Models\RetailerOrder;
use Emrad\Models\StockHistory;
use Emrad\Models\RetailerInventory;
use Emrad\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Support\Facades\Validator;
use Exception;
use DB;

class OrderServices
{
    /**
     * @var $orderRepositoryInterface
     */
    public $orderRepositoryInterface;

    public function __construct(OrderRepositoryInterface $orderRepositoryInterface)
    {
        $this->orderRepositoryInterface = $orderRepositoryInterface;
    }

    /**
     * Create a new order
     *
     * @param Request $request
     * @param user_id
     *
     * @return \Emrad\Models\RetailerOrder $order
     */

    public function createRetailerOrder($order, $user_id)
    {
        $product = Product::find($order['product_id']);

        $retailerOrder = new RetailerOrder;
        $retailerOrder->product_id = $product->id;

        if(array_key_exists('company_id', $order))
            $retailerOrder->company_id = $order['company_id'];

        $retailerOrder->unit_price = $product->price;
        $retailerOrder->selling_price = $product->selling_price;
        $retailerOrder->quantity = $order['quantity'];
        $retailerOrder->order_amount = $retailerOrder->unit_price * $retailerOrder->quantity;
        $retailerOrder->created_by = $user_id;
        $retailerOrder->save();

        return $retailerOrder;
    }



    public function makeRetailerOrder($orders, $user_id)
    {
        DB::beginTransaction();
        try {
            foreach ($orders as $order) {
                $validator = Validator::make($order, [
                    'product_id' => 'bail|required|numeric',
                    'company_id' => 'nullable',
                    'quantity' => 'required|numeric',
                ]);

                if ($validator->fails()) {
                    throw new Exception("validation failed, please check request");
                }

                $retailerOrder = $this->createRetailerOrder($order, $user_id);
            }

            dispatch(new SendMailToDistributorEmailJob($user_id));

            DB::commit();
            return "Order created successfully!";

        } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }



    public function getStockBalance($product_id)
    {
        $count = RetailerInventory::where('product_id', $product_id)->count();
        if($count > 0) {
            $isInInventory = true;
            $inventory = RetailerInventory::where('product_id', $product_id)->first();
            $stockBalance = $inventory->quantity;
            return [$isInInventory, $stockBalance];
        } else {
            $isInInventory = false;
            $stockBalance = "Product not in stock";
            return [$isInInventory, $stockBalance];
        }

    }

    /**
     * Get single retailer-order
     *
     * @param $order_id
     */
    public function getSingleRetailerOrder($order_id)
    {
        return $this->orderRepositoryInterface->find($order_id);
    }

    /**
     * Get all retailer orders
     *
     * @param \Collection $order
     */
    public function getAllRetailerOrders()
    {
        return $this->orderRepositoryInterface->paginate(10);
    }


    /**
     * Delete the requested order
     *
     * @param Int|String $id
     *
     * @return void
     */
    public function delete($order_id)
    {
        $order = $this->orderRepositoryInterface->find($order_id);

        $order->delete();
    }

    /**
     * Fine the requested order by Id
     * Then Update the order with the $request
     *
     * @param Object $request
     * @param Int|String $id
     *
     * @return \Spatie\Permission\Models\Order
     */
    public function confirmRetailerOrder($order_id, $user_id)
    {
        DB::beginTransaction();
        try {
            $retailerOrder = RetailerOrder::find($order_id);

            $isNull = is_null($retailerOrder);

            if($isNull)
                throw new Exception("Order not found!");

            if($retailerOrder->is_confirmed)
                throw new Exception("Order already confirmed");

            $updateInventory = $this->updateInventory($retailerOrder, $user_id);

            if(!$updateInventory)
                throw new Exception("Inventory not updated");

            $retailerOrder->is_confirmed = true;
            $retailerOrder->save();

            DB::commit();
            return "Order confirmed, inventory updated!";

        } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

    public function updateInventory($retailerOrder, $user_id)
    {
        try {

            $retailerInventory = RetailerInventory::firstOrNew([
                'product_id' => $retailerOrder->product_id
            ]);

            $product_id = $retailerInventory->product_id;
            if(is_null($retailerInventory->quantity)) {
                $currentStockBalance = 0;
            } else {
                $currentStockBalance = $retailerInventory->quantity;
            }

            $retailerInventory->quantity = $retailerInventory->quantity + $retailerOrder->quantity;
            $newStockBalance = $retailerInventory->quantity;

            $stockHistory = new StockHistory;
            $stockHistory->product_id = $product_id;
            $stockHistory->user_id = $user_id;
            $stockHistory->stock_balance = $currentStockBalance;
            $stockHistory->new_stock_balance = $newStockBalance;

            $retailerInventory->cost_price = $retailerOrder->unit_price;
            $retailerInventory->selling_price = $retailerOrder->selling_price;
            $retailerInventory->is_in_stock = $retailerOrder->quantity == 0 ? 0 : 1;
            $retailerInventory->save();

            $inventory_id = $retailerInventory->id;
            $stockHistory->inventory_id = $inventory_id;
            $stockHistory->save();

            return true;
        } catch(Exception $e) {
            return false;
        }
    }

    public function updateStockHistory($retailerInventory, $retailerOrder, $user_id)
    {
        try {
            $product_id = $retailerInventory->product_id;
            $inventory_id = $retailerInventory->id;
            $currentStockBalance = $retailerInventory->quantity;

            $newStockBalance = $retailerInventory->quantity + $retailerOrder->quantity;

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
