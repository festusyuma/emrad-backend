<?php

namespace Emrad\Services;

use Emrad\Models\Order;
use Emrad\Models\OrderItems;
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
    public OrderRepositoryInterface $orderRepositoryInterface;
    public TransactionService $transactionService;

    public function __construct(
        OrderRepositoryInterface $orderRepositoryInterface,
        TransactionService $transactionService
    )
    {

        $this->orderRepositoryInterface = $orderRepositoryInterface;
        $this->transactionService = $transactionService;
    }

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

    public function makeRetailerOrder($orderReq, $user): CustomResponse
    {
        try {
            $orders = $orderReq['items'];
            $payment_method = strtolower($orderReq['payment_method']);
            $card_id = $orderReq['card_id'];

            if (!in_array($payment_method, ['wallet', 'card', 'paystack'])) {
                return CustomResponse::badRequest('invalid payment type');
            } else if ($payment_method == 'card' && !$card_id) {
                return CustomResponse::badRequest('please select a card');
            }

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

            DB::beginTransaction();

            $order = new Order([
                'amount' => 0,
                'user_id' => $user->id,
            ]);
            $order->save();

            $generateItemsRes = $this->getOrderItems($orders, $order->id);
            if (!$generateItemsRes->success) return $generateItemsRes;
            $generateItems = $generateItemsRes->data;

            $orderItems = $generateItems['items'];
            $totalAmount = $generateItems['totalAmount'];
            $order->amount = $totalAmount;
            $order->save();

            $chargeRes = $this->chargeCustomer($user, $order, $payment_method, $card_id);
            if (!$chargeRes->success) {
                DB::rollBack();
                return $chargeRes;
            }

            $charge = $chargeRes->data;
            $order->transaction_id = $payment_method === 'paystack' ? $charge['transaction']->id : $charge->id;
            $order->save();

            DB::commit();
            DB::beginTransaction();

            if ($payment_method === 'paystack') {
                return CustomResponse::success($charge);
            }

            if ($charge->status == 'success') {
                $order->payment_confirmed = true;
                $charge->verified = true;
                $charge->save();
                $order->save();

                DB::commit();
                return CustomResponse::success($charge);
            } else {
                $this->revertStock($orderItems);
                DB::commit();
                return CustomResponse::failed('error placing order');
            }
        } catch (\Exception $e) {
            return CustomResponse::serverError($e);
        }
    }

    private function getOrderItems($orders, $order_id): CustomResponse
    {
        try {
            $orderItems = array();
            $totalAmount = 0;

            $productIds = array_column($orders, 'product_id');
            $products = Product::whereIn('id', $productIds)->orderBy('id', 'ASC')->get();
            if (count($products) < count($productIds)) return CustomResponse::badRequest("invalid product id");

            for ($i = 0; $i < count($orders); $i++) {
                $orderItem = new OrderItems([
                    'product_id' => $products[$i]->id,
                    'quantity' => $orders[$i]['quantity'],
                    'unit_price' => floatval($products[$i]->price),
                    'amount' => $products[$i]->price * $orders[$i]['quantity'],
                    'order_id' => $order_id
                ]);

                if ($products[$i]->size < $orderItem->quantity) {
                    return CustomResponse::failed($products[$i]->name.' does not have enough stock');
                } else {
                    $products[$i]->size -= $orderItem->quantity;
                    $products[$i]->save();
                    $orderItem->save();
                }

                $orderItems[] = $orderItem;
                $totalAmount += $orderItem->amount;
            }

            return CustomResponse::success([ 'items' => $orderItems, 'totalAmount' => $totalAmount ]);
        } catch (\Exception $e) {
            return CustomResponse::serverError($e);
        }
    }

    private function chargeCustomer($user, $order, $payment_method, $card_id = null): CustomResponse
    {
        try {
            $paymentData = [
                'amount' => $order['amount'],
                'email' => $user->email,
                'channels' => ['card'],
                'user_id' => $user->id
            ];

            switch ($payment_method) {
                case 'wallet':
                    return $this->transactionService->chargeWallet(
                        config('transactiontype.retail_order'),
                        $paymentData,
                    );
                case 'card':
                    return $this->transactionService->chargeCard(
                        $card_id,
                        config('transactiontype.retail_order'),
                        $paymentData,
                    );
                case 'paystack':
                    return $this->transactionService->initTransaction(
                        config('transactiontype.retail_order'),
                        $paymentData,
                    );
                default:
                    return CustomResponse::badRequest('invalid payment method');
            }
        } catch (\Exception $e) {
            return CustomResponse::serverError($e);
        }
    }

    private function revertStock($items) {
        try {
            foreach ($items as $item) {
                $product = Product::find($item->product_id)->get();
                if (!$product) return;

                $product->size += $item->quantity;
                $product->save();
            }
        } catch (\Exception $e) {
            error_log($e->getMessage());
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
