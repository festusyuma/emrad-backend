<?php

namespace Emrad\Http\Controllers\Distributor;

use Emrad\Http\Controllers\Controller;
use Emrad\Http\Requests\UpdateProductRequest;
use Emrad\Models\Product;
use Emrad\Services\Distributor\ProductsServices;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    private ProductsServices $productsServices;

    public function __construct(ProductsServices $productsServices)
    {
        $this->productsServices = $productsServices;
    }

    public function createProduct(Request $request)
    {
        $result = $this->productsServices->createProduct($request);

        return response([
            'status' => $result->success,
            'message' => $result->message,
            'data' => $result->data
        ], $result->status);
    }

    public function updateProduct(UpdateProductRequest $request, Product $product)
    {
        $result = $this->productsServices->updateProduct($request->all(), $product);

        return response([
            'status' => $result->success,
            'message' => $result->message,
            'data' => $result->data
        ], $result->status);
    }

    public function deleteProduct(Product $product)
    {
        $result = $this->productsServices->deleteProduct( $product);

        return response([
            'status' => $result->success,
            'message' => $result->message,
            'data' => $result->data
        ], $result->status);
    }

    public function getProducts(Request $request)
    {
        $limit = $request->get('size', 10);
        $result = $this->productsServices->fetchProducts($limit);

        return response([
            'status' => $result->success,
            'message' => $result->message,
            'data' => $result->data
        ], $result->status);
    }

    public function getStats(Request $request)
    {
        $result = $this->productsServices->fetchProductStats();

        return response([
            'status' => $result->success,
            'message' => $result->message,
            'data' => $result->data
        ], $result->status);
    }
}
