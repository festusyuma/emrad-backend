<?php

namespace Emrad\Repositories;

use Emrad\Models\Offer;
use Emrad\Repositories\Contracts\OfferRepositoryInterface;


class OfferRepository extends BaseRepository implements OfferRepositoryInterface {

    public $offer;

    /**
     * OfferRepository Constructor
     *
     * @param Emrad\Models\Offer $offer
      */
    public function __construct(Offer $offer)
    {
        $this->model = $offer;
    }
}
