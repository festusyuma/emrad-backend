<?php

namespace Emrad\Services;

use Emrad\Services\InventoryServices;
use Emrad\Models\Product;
use Emrad\User;
use Emrad\Models\RetailerOrder;
use Emrad\Models\StockHistory;
use Emrad\Models\RetailerInventory;
use Emrad\Events\NewRetailerOrderEvent;
use Emrad\Repositories\Contracts\OrderRepositoryInterface;
use Emrad\Util\CustomResponse;
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
     * @throws Exception
     */
    public function createRetailerOrder($order, $user_id): RetailerOrder
    {
        $product = Product::find($order['product_id']);
        if(!$product) throw new Exception("product not found");

        $retailerOrder = new RetailerOrder;
        $retailerOrder->product_id = $product->id;
        $retailerOrder->user_id = $user_id;

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

    /**
     * @throws Exception
     */
    public function makeRetailerOrder($orders, $user_id)
    {
        if (count($orders) < 1) return CustomResponse::badRequest("order cannot be empty order");
        usort($orders, function ($a, $b) { return $a['product_id'] > $b['product_id']; });

        $validator = Validator::make($orders, [
            '*.product_id' => 'bail|required|integer',
            '*.company_id' => 'nullable',
            '*.quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return CustomResponse::badRequest("validation failed, plaese check request");
        }

        $productIds = array_column($orders, 'product_id');
        $products = Product::whereIn('id', $productIds)->orderBy('id', 'ASC')->get();
        if (count($products) < count($productIds)) return CustomResponse::badRequest("invalid product id");

        DB::beginTransaction();

        $orderItems = array();
        for ($i = 0; $i < count($orders); $i++) {
            $orderItem = [
                'product_id' => $products[$i]->id,
                'quantity' => $orders[$i]['quantity'],
                'price_per_unit' => floatval($products[$i]->price),
                'amount' => $products[$i]->price * $orders[$i]['quantity']
            ];

            if ($products[$i]->size < $orderItem['quantity']) {
                return CustomResponse::failed($products[$i]->name.' does not have enough stock');
            }

            $orderItems[] = $orderItem;
        }

        dd($orderItems);
        try {
            error_log(count($orders));
            $retailerOrders = [];

            foreach ($orders as $order) {
                $retailerOrder = $this->createRetailerOrder($order, $user_id);
                $retailerOrders[] = $retailerOrder;
            }

            $user = User::find($user_id);
            if($user) event(new NewRetailerOrderEvent($user, $retailerOrders));

            DB::commit();
            return "Order created successfully!";

        } catch (Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
    }

    public function getStockBalance($product_id): array
    {
        $count = RetailerInventory::where('product_id', $product_id)->count();
        $isInInventory = $count > 0;

        if($count > 0) {
            $inventory = RetailerInventory::where('product_id', $product_id)->first();
            $stockBalance = $inventory->quantity;
        } else {
            $stockBalance = "Product not in stock";
        }

        return [$isInInventory, $stockBalance];
    }

    public function getSingleRetailerOrder($order_id, $user_id): \Emrad\Repositories\Model
    {
        return $this->orderRepositoryInterface->findByUser($order_id, $user_id);
    }

    public function getAllRetailerOrders($user_id, $limit)
    {
        return $this->orderRepositoryInterface->paginateAllByUser($user_id, $limit);
    }

    public function delete($order_id)
    {
        $order = $this->orderRepositoryInterface->find($order_id);

        $order->delete();
    }

    public function confirmRetailerOrder($order_id, $user_id): string
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

    public function updateInventory($retailerOrder, $user_id): bool
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

            $retailerInventory->user_id = $user_id;
            $retailerInventory->quantity = $retailerInventory->quantity + $retailerOrder->quantity;
            $newStockBalance = $retailerInventory->quantity;

            $stockHistory = new StockHistory;
            $stockHistory->product_id = $product_id;
            $stockHistory->user_id = $user_id;
            $stockHistory->stock_balance = $currentStockBalance;
            $stockHistory->new_stock_balance = $newStockBalance;
            $stockHistory->is_depleted = false;

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
