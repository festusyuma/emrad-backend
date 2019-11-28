<?php

namespace Emrad\Repositories\Contracts;


interface ImageRepositoryInterface extends BaseRepositoryInterface {

    /**
     * find by productId
     * @param string $productId
     *
     * @return Image $image
     */
    public function findByProductId($productId);
    
}
