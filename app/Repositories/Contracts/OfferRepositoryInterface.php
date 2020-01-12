<?php

namespace Emrad\Repositories\Contracts;


interface OfferRepositoryInterface extends BaseRepositoryInterface {

    /**
     * get list of user offers
     */
    public function myOffers($user);
}
