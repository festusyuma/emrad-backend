<?php

namespace Emrad\Services\Distributor;

use Emrad\Repositories\Contracts\OrderRepositoryInterface;
use Emrad\Util\CustomResponse;

class OrdersServices
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function fetchOrders($limit, $filters = []): CustomResponse
    {
        try {
            $orders = $this->orderRepository->fetchByProductOwner(auth()->id(), $limit);
            return CustomResponse::success($orders);
        } catch (\Exception $e) {
            return CustomResponse::serverError($e);
        }
    }

    public function fulfillOrder($orderId): CustomResponse
    {
        try {
            return CustomResponse::success();
        } catch (\Exception $e) {
            return CustomResponse::serverError($e);
        }
    }
}
