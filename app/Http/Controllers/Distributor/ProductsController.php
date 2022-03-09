<?php

namespace Emrad\Http\Controllers\Distributor;

use Emrad\Http\Controllers\Controller;
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

    public function updateProduct()
    {

    }

    public function getProducts()
    {

    }
}
