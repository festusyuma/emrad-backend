<?php

namespace Emrad\Services\Distributor;

use Emrad\Repositories\Contracts\OrderRepositoryInterface;
use Emrad\Repositories\Contracts\ProductRepositoryInterface;
use Emrad\Repositories\Contracts\WalletRepositoryInterface;
use Emrad\Util\CustomResponse;

class IndexService
{
    private OrderRepositoryInterface $orderRepository;
    private ProductRepositoryInterface $productRepository;
    private WalletRepositoryInterface $walletRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ProductRepositoryInterface $productRepository,
        WalletRepositoryInterface $walletRepository
    )
    {
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
        $this->walletRepository = $walletRepository;
    }

    public function fetchStats(): CustomResponse
    {
        try {
            $totalProducts = $this->productRepository->countAllByUser(auth()->id());
            $totalSales = $this->orderRepository->countByProductOwner(auth()->id(), [['confirmed', true]]);
            $totalIncome = $this->orderRepository->countAmountProductOwner(auth()->id(), [['confirmed', true]]);

            $stats = [
                'income' => $totalIncome,
                'products' => $totalProducts,
                'sales' => $totalSales
            ];

            return CustomResponse::success($stats);
        } catch (\Exception $e) {
            return CustomResponse::serverError($e);
        }
    }

    public function fetchHistory(): CustomResponse
    {
        try {
            $salesHistory = $this->orderRepository->saleHistoryByProductOwner(auth()->id(), [['confirmed', true]]);
            $creditHistory = $this->walletRepository->history(auth()->id());

            $stats = [
                'sales' => $salesHistory,
                'income' => $creditHistory
            ];

            return CustomResponse::success($stats);
        } catch (\Exception $e) {
            return CustomResponse::serverError($e);
        }
    }
}
