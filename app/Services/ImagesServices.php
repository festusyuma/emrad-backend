<?php

namespace Emrad\Services;

use Emrad\Models\Image;
use Emrad\Repositories\Contracts\ImageRepositoryInterface;

class ImagesServices
{
    /**
     * @var $imageRepositoryInterface
     */
    public $imageRepositoryInterface;

    public function __construct(ImageRepositoryInterface $imageRepositoryInterface)
    {
        $this->imageRepositoryInterface = $imageRepositoryInterface;
    }

    /**
     * Create a new Image Instance
     *
     * @param String $imagePath
     * @param String $productID
     *
     * @return Emrad\Models\Product
     */

    public function createImage(
        $imagePath,
        $productID
    )
    {
        // instatiate a new class
        $image = new Image;

        try {
            $image->image = $imagePath;
            $image->product_id = $productID;
            $image->save();

            return $image;
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Update a new Image Instance
     *
     * @param String $imagePath
     * @param String $productID
     *
     * @return Emrad\Models\Product
     */

    public function updateImage(
        Image $image,
        $imagePath,
        $productId
    )
    {
        try {
            $image->image = $imagePath;
            $image->product_id = $productId;
            $image->save();

            return $image;
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * find image by product id
     * @param string $productId
     */
    public function findImage($productId)
    {
        return $this->imageRepositoryInterface->findByProductId($productId);
    }

}


