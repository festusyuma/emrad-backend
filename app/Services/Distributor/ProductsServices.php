<?php

namespace Emrad\Services\Distributor;

use Emrad\Models\Product;
use Emrad\Repositories\Contracts\OrderRepositoryInterface;
use Emrad\Repositories\Contracts\ProductRepositoryInterface;
use Emrad\Util\CustomResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductsServices
{
    private ProductRepositoryInterface $productRepository;
    private OrderRepositoryInterface $orderRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        OrderRepositoryInterface $orderRepository
    )
    {
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
    }

    public function createProduct(Request $request): CustomResponse
    {
        try {
            try {
                $data = $request->validate([
                    'name' => 'required|string',
                    'description' => 'required|string',
                    'thumbnail' => 'string|nullable',
                    'stock' => 'required|integer|min:1',
                    'sku' => 'required|string',
                    'price' => 'required|numeric|min:0',
                    'sellingPrice' => 'required|numeric|min:0.1',
                    'categoryId' => 'required|exists:categories,id',
                ]);
            } catch (\Exception $e) {
                return CustomResponse::badRequest('invalid data');
            }

            $product = new Product();
            $product->name = $data['name'];
            $product->description = $data['description'];
            $product->size = $data['stock'];
            $product->price = $data['price'];
            $product->selling_price = $data['sellingPrice'];
            $product->sku = $data['sku'];
            $product->category_id = $data['categoryId'];
            $product->user_id = auth()->id();
            $product->save();

            $message = 'successful';
            if ($data['thumbnail']) {
                try {
                    $result = cloudinary()->uploadFile($data['thumbnail']);
                    $uploadedUrl = $result->getSecurePath();
                    $product->image = $uploadedUrl;
                    $product->save();
                } catch (\Exception $e) {
                    $message = 'successful, but could not upload thumbnail';
                }
            }

            return CustomResponse::success($product, $message);
        } catch (\Exception $e) {
            return CustomResponse::serverError($e);
        }
    }

    public function fetchProducts($limit=10): CustomResponse
    {
        try {

            $products = $this->productRepository->paginateAllByUser(
                auth()->id(),
                $limit,
                ['category']
            );
            return CustomResponse::success($products);
        } catch (\Exception $e) {
            return CustomResponse::serverError($e);
        }
    }

    public function fetchProductStats(): CustomResponse
    {
        try {
            $totalProducts = $this->productRepository->countAllByUser(auth()->id());
            $totalStock = $this->productRepository->countAllStockByUser(auth()->id());
            $approvedProducts = $this->productRepository->countAllByUser(auth()->id(), [['approved', true]]);
            $unApprovedProducts = $totalProducts - $approvedProducts;
            $stockSold = $this->orderRepository->countStockByProductOwner(auth()->id());

            $stats = [
                'total' => $totalProducts,
                'stock' => $totalStock,
                'sold' => $stockSold,
                'approved' => $approvedProducts,
                'pending' => $unApprovedProducts
            ];

            return CustomResponse::success($stats);
        } catch (\Exception $e) {
            return CustomResponse::serverError($e);
        }
    }
}
