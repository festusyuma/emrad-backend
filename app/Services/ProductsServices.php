<?php

namespace Emrad\Services;

use Emrad\Models\Product;
use Emrad\Repositories\Contracts\ProductRepositoryInterface;

class ProductsServices
{
    /**
     * @var $productRepositoryInterface
     */
    public $productRepositoryInterface;

    public function __construct(ProductRepositoryInterface $productRepositoryInterface)
    {
        $this->productRepositoryInterface = $productRepositoryInterface;
    }

    /**
     * Create a new Product Instance
     *
     * @param String $productName
     * @param String $userId
     * @param String $productDescription
     * @param String $productPrice
     * @param String $productSize
     * @param String $productImage
     * @param String $productSku
     *
     * @return Emrad\Models\Product
     */

    public function createProduct(
        $categoryId,
        $userId,
        $productName,
        $productDescription,
        $productPrice,
        $productSize,
        $productImage,
        $productSku
    )
    {
        // instatiate a new class
        $product = new Product;

        try {
            $product->category_id = $categoryId;
            $product->user_id = $userId;
            $product->name = $productName;
            $product->description = $productDescription;
            $product->price = $productPrice;
            $product->size = $productSize;
            $product->image = $productImage;
            $product->sku = $productSku;

            $product->save();
            return $product;
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Update an existing merchant Instance
     *
     * @param Emrad\Models\Product $product
     * @param String $categoryId
     * @param String $userId
     * @param String $productName
     * @param String $productDescription
     * @param String $productPrice
     * @param String $productSize
     * @param String $productImage
     *
     * @return Emrad\Models\Product
      */

      public function updateProduct(
        Product $product,
        $categoryId,
        $userId,
        $productName,
        $productDescription,
        $productPrice,
        $productSize,
        $productImage
    )
    {
        try {
            $product->category_id = $categoryId;
            $product->user_id = $userId;
            $product->name = $productName;
            $product->description = $productDescription;
            $product->price = $productPrice;
            $product->size = $productSize;
            $product->image = $productImage;
            $product->save();

            return $product;
        } catch (\Exception $e) {
            return $e;
        }

    }

    /**
     * return all Product
     *
     * @return \Collection $products
     */
    public function getProducts()
    {
        return $this->productRepositoryInterface->paginate(20);
    }

    /**
     * return all find products
     *
     * @param \Collection $product
     */
    public function getSingleProduct($id)
    {
        return $this->productRepositoryInterface->find($id);
    }

    /**
     * Toogle Product as liked or unlike
     *
     * @param Request $request
     *
     * @return Emrad\Models\Product $product
     */
    public function toogleLike($request)
    {
        // get the auth user
        $authUser = auth()->user();

        // find the product by id
        $product = $this->productRepositoryInterface->find($request->productId);

        // toggles the user like option
        $authUser->toggleLike($product);

        return $product;

    }

    /**
     * Increment the view count colunm in the specified product colunm
     * @param Product $product
     *
     */
    public function incrementProductViewCount($product)
    {
        $productCount = $product->views_count;
        $product->views_count = $productCount +1;
        $product->save();
        return;
    }

    /**
     * user delete personal product after upload
     *
     * @param Product $product
     *
     * @return void
     */
    public function deleteProduct($product)
    {
        return $product->delete();
    }
}


