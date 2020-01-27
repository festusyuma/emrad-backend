<?php

namespace Emrad\Http\Controllers;

use Illuminate\Http\Request;
use Emrad\Models\Product;
use Illuminate\Support\Facades\Input;
use Emrad\Events\NewProductView;
use Emrad\Filters\ProductFilters;
use Emrad\Services\FilesServices;
use Emrad\Services\ImagesServices;
use Emrad\Services\ProductsServices;
use Emrad\Http\Requests\CreateProduct;
use Emrad\Http\Resources\ProductsResource;
use Emrad\Http\Resources\ProductCollection;
use Emrad\Http\Requests\UpdateProductRequest;

class ProductsController extends Controller
{

    /**
     * @var ProductsServices $productsServices
     */
    public $productsServices;

    /**
     * @var FilesServices $filesServices
     */
    public $filesServices;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(ProductsServices $productsServices, FilesServices $filesServices,ImagesServices $imagesServices)
    {
        $this->productsServices = $productsServices;
        $this->filesServices = $filesServices;
        $this->imagesServices = $imagesServices;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProducts(ProductFilters $filters)
    {
        // filters base on the resquest parameters
        $products = Product::filter($filters)->get();
        // ->orderBy('id', 'desc')->paginate(16)
        // ->setPath(route('list-products', Input::except('page')));

        // ->appends(Input::except('page'));
        return new ProductCollection($products);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Emrad\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function getSingleProduct($product)
    {
        $product = $this->productsServices->getSingleProduct($product);

        return response([
            'status' => 'success',
            'message' => 'product detail',
            'data' => new ProductsResource($product)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function createProduct(CreateProduct $request)
    {
        $pathToFile = $this->filesServices->uploadBase64($request->image, 's3');

        $productSku = base_convert(microtime(true), 10, 36);

        $product = $this->productsServices->createProduct(
                                                            $request->categoryId,
                                                            auth("api")->user()->id,
                                                            $request->productName,
                                                            $request->productDescription,
                                                            $request->productPrice,
                                                            $request->productSellingPrice,
                                                            $request->productSize,
                                                            $pathToFile,
                                                            $productSku
                                                        );
        return response([
                            'status' => 'success',
                            'message' => 'product created successfully',
                            'data' => new ProductsResource($product)
                        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Emrad\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function updateProduct(UpdateProductRequest $request, Product $product)
    {
        $pathToFile = $this->filesServices->uploadBase64($request->image, 's3');

        $product = $this->productsServices->updateProduct(
                                                            $product,
                                                            $request->categoryId,
                                                            auth("api")->user()->id,
                                                            $request->productName,
                                                            $request->productDescription,
                                                            $request->productPrice,
                                                            $request->productSellingPrice,
                                                            $request->productSize,
                                                            $request->productImage,
                                                            $request->productSku
                                                        );
        return response([
                            'status' => 'success',
                            'message' => 'product updated successfully',
                            'data' => new ProductsResource($product)
                        ], 200);
    }
}
