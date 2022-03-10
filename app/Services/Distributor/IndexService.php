<?php

namespace Emrad\Services\Distributor;

use Emrad\Repositories\Contracts\OrderRepositoryInterface;
use Emrad\Repositories\Contracts\ProductRepositoryInterface;
use Emrad\Util\CustomResponse;

class IndexService
{
    private OrderRepositoryInterface $orderRepository;
    private ProductRepositoryInterface $productRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ProductRepositoryInterface $productRepository
    )
    {
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
    }

    public function fetchStats(): CustomResponse
    {
        try {
            $totalProducts = $this->productRepository->countAllByUser(auth()->id());
            $totalSales = $this->orderRepository->countByProductOwner(auth()->id(), [['confirmed', true]]);

            $stats = [
                'income' => 0,
                'products' => $totalProducts,
                'sales' => $totalSales
            ];

            return CustomResponse::success($stats);
        } catch (\Exception $e) {
            return CustomResponse::serverError($e);
        }
    }
}
