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

    public function fetchStats(): CustomResponse
    {
        try {
            $totalOrders = $this->orderRepository->countByProductOwner(auth()->id());
            $totalConfirmed = $this->orderRepository->countByProductOwner(auth()->id(), [['confirmed', true]]);
            $totalPending = $totalOrders - $totalConfirmed;
            $stockSold = $this->orderRepository->countStockByProductOwner(auth()->id());

            $data = [
                'total' => $totalOrders,
                'soldStock' => $stockSold,
                'confirmed' => $totalConfirmed,
                'pending' => $totalPending
            ];

            return CustomResponse::success($data);
        } catch (\Exception $e) {
            return CustomResponse::serverError($e);
        }
    }

    public function fetchOrders($limit, $filters = []): CustomResponse
    {
        try {
            $orders = $this->orderRepository->fetchByProductOwner(auth()->id(), $limit, $filters);
            return CustomResponse::success($orders);
        } catch (\Exception $e) {
            return CustomResponse::serverError($e);
        }
    }

    public function fetchCustomerOrders($customerId, $limit, $filters = []): CustomResponse
    {
        try {
            $orders = $this->orderRepository->fetchByProductOwnerAndCustomer(auth()->id(), $customerId, $limit, $filters);
            return CustomResponse::success($orders);
        } catch (\Exception $e) {
            return CustomResponse::serverError($e);
        }
    }

    public function fetchTopRetailers($filters = []): CustomResponse
    {
        try {
            $orders = $this->orderRepository->topRetailersByProductOwner(auth()->id(), $filters);
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
