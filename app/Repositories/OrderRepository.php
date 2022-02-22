<?php

namespace Emrad\Repositories;

use Emrad\Models\Order;
use Emrad\Models\RetailerOrder;
use Emrad\Repositories\Contracts\OrderRepositoryInterface;


class OrderRepository extends BaseRepository implements OrderRepositoryInterface {

    private Order $model;

    public function __construct(Order $retailerOrder)
    {
        $this->model = $retailerOrder;
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

}
