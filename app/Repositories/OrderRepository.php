<?php

namespace Emrad\Repositories;

use DB;
use Emrad\Models\Order;
use Emrad\Models\OrderItems;
use Emrad\Models\RetailerOrder;
use Emrad\Repositories\Contracts\OrderRepositoryInterface;


class OrderRepository extends BaseRepository implements OrderRepositoryInterface {

    private Order $model;
    private OrderItems $itemsModel;

    public function __construct(Order $retailerOrder, OrderItems $itemsModel)
    {
        $this->model = $retailerOrder;
        $this->itemsModel = $itemsModel;
    }



    public function allByUser(): \Illuminate\Support\Collection
    {
        return $this->model
                    ->select()
                    ->orderBy('id', 'DESC')
                    ->get();
    }


    public function paginateAllByUser($user_id, $limit, $relations = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->model::with($relations)
            ->where('user_id', $user_id)
            ->orderBy('created_at', 'DESC')->paginate($limit);
    }

    public function totalOrderPayment($user_id)
    {
        return $this->model::where([
            ['user_id', $user_id],
            ['payment_confirmed', true]
        ])->sum('amount');
    }


    public function findByUser($order_id, $user_id, $relations = [])
    {
        return $this->model
                ->where('id', $order_id)
                ->where('user_id', $user_id)
                ->with($relations)
                ->firstOrFail();

    }

    public function fetchByProductOwner($user_id, $limit, $filters = []): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->buildOwnerQuery($user_id, $filters)->with(['product', 'order'])->paginate($limit);
    }

    public function countByProductOwner($user_id, $filters = []): int
    {
        return $this->buildOwnerQuery($user_id, $filters)->count();
    }

    public function countStockByProductOwner($user_id, $filters = []): int
    {
        return $this->buildOwnerQuery($user_id, $filters)->sum('quantity');
    }

    public function countAmountProductOwner($user_id, $filters)
    {
        return $this->buildOwnerQuery($user_id, $filters)->sum('amount');
    }

    public function saleHistoryByProductOwner($user_id, $group)
    {
        try {
            return $this->buildOwnerQuery($user_id, [])
                ->select(DB::raw("count(id) as `total`, DATE_FORMAT(created_at, '%d-%m-%Y') period"))
                ->groupby('period')
                ->get();
        } catch (\Exception $e) {
            error_log('repository error');
            error_log($e->getMessage());
            return null;
        }
    }

    public function topRetailersByProductOwner($user_id, $filters)
    {
        try {
            $res =  $this->buildOwnerQuery($user_id, [])
                ->leftJoin('orders', 'orders.id', '=', 'order_id')
                ->select(DB::raw('sum(quantity) as `total`, sum(orders.amount) as `total_amount`, orders.user_id'))
                ->groupBy('orders.user_id');

            return $res->get();

        } catch (\Exception $e) {
            error_log('repository error');
            error_log($e->getMessage());
            return null;
        }
    }

    private function buildOwnerQuery($user_id, $filters): ?\Illuminate\Database\Eloquent\Builder
    {
        try {
            return $this->itemsModel
                ->where($filters)
                ->whereHas('product', function ($query) use ($user_id) {
                    $query->where('user_id', $user_id);
                })
                ->whereHas('order', function ($query) {
                    $query->where('payment_confirmed', true);
                });
        } catch (\Exception $e) {
            error_log('repository error');
            error_log($e->getMessage());
            return null;
        }
    }
}
