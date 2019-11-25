<?php

namespace Emrad\Repositories;

use Emrad\Models\Image;
use Emrad\Repositories\Contracts\ImageRepositoryInterface;

class ImageRepository extends BaseRepository implements ImageRepositoryInterface {

    public $model;

    /**
     * ImageRepository Constructor
     *
     * @param Emrad\Models\Image $image
      */
    public function __construct(Image $image)
    {
        $this->model = $image;
    }

    /**
     * find by productId
     * @param string $productId
     *
     * @return Image $image
     */
    public function findByProductId($productId)
    {
        return $this->model->where('product_id', $productId)->first();
    }
}
