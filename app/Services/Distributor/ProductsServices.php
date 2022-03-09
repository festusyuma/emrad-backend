<?php

namespace Emrad\Services\Distributor;

use Emrad\Models\Product;
use Emrad\Repositories\ProductRepository;
use Emrad\Util\CustomResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductsServices
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function createProduct(Request $request): CustomResponse
    {
        try {
            try {
                $data = $request->validate([
                    'name' => 'required|string',
                    'description' => 'required|string',
                    'thumbnail' => 'string',
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
}
