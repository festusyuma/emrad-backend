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

    /**
     * get list of user offers
     *
     * @param user $user
     */
    public function myOffers($user)
    {
        return $user->offers()->paginate(10);
    }
}
