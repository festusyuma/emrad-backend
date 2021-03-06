<?php

namespace Emrad\Services;

use Emrad\Models\Offer;
use Emrad\Repositories\Contracts\OfferRepositoryInterface;

class OffersServices
{
    /**
     * @var $offerRepositoryInterface
     */
    public $offerRepositoryInterface;

    public function __construct(OfferRepositoryInterface $offerRepositoryInterface)
    {
        $this->offerRepositoryInterface = $offerRepositoryInterface;
    }

    /**
     * Create a new offer Instance
     *
     * @param String $productId
     * @param String $offerTitle
     * @param String $offerDescription
     * @param String $pathToFile
     * @param String $offerStartDate
     * @param String $offerStartDate
     *
     * @return Emrad\Models\Offer
     */

    public function createOffer(
        $productId,
        $offerTitle,
        $pathToFile,
        $offerProfitMargin,
        $offerDescription,
        $offerStartDate,
        $offerEndDate
    )
    {
        // instatiate a new class
        $offer = new Offer();

        try {
            $offer->product_id = $productId;
            $offer->title = $offerTitle;
            $offer->image = $pathToFile;
            $offer->profit_margin = $offerProfitMargin;
            $offer->description = $offerDescription;
            $offer->start_date = $offerStartDate;
            $offer->end_date = $offerEndDate;
            $offer->save();

            return $offer;
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Update an existing offer Instance
     *
     * @param Emrad\Models\Offer $offer
     * @param String $productId
     * @param String $offerTitle
     * @param String $offerDescription
     * @param String $pathToFile
     * @param String $offerStartDate
     * @param String $offerStartDate
     *
     * @return Emrad\Models\offer
      */

      public function updateOffer(
        Offer $offer,
        $productId,
        $offerTitle,
        $offerDescription,
        $pathToFile,
        $offerProfitMargin,
        $offerStartDate,
        $offerEndDate
    )
    {
        try {
            $offer->product_id = $productId;
            $offer->title = $offerTitle;
            $offer->image = $pathToFile;
            $offer->profit_margin = $offerProfitMargin;
            $offer->description = $offerDescription;
            $offer->start_date = $offerStartDate;
            $offer->end_date = $offerEndDate;
            $offer->save();

            return $offer;
        } catch (\Exception $e) {
            return $e;
        }

    }

    /**
     * return all offers
     *
     * @return \Collection $offers
     */
    public function getOffers()
    {
        return $this->offerRepositoryInterface->paginate(20);
    }

    /**
     * return all find offer
     *
     * @param \Collection $offer
     */
    public function getSingleOffer($id)
    {
        return $this->offerRepositoryInterface->find($id);
    }

    /**
     * Get the authenticated user offers
     *
     * @param user $user
     *
     * @return Offer $offer
     */
    public function myOffers($user)
    {
        return  $this->offerRepositoryInterface->myOffers($user);
    }
}


