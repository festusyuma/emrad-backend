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
     * Create a new category Instance
     *
     * @param String $categoryName
     * @param String $categoryDescription
     * @param String $categoryLogo
     *
     * @return Emrad\Models\Offer
     */

    public function createCategory(
        $categoryName,
        $categoryDescription,
        $categoryLogo
    )
    {
        // instatiate a new class
        $category = new Offer();

        try {
            $category->name = $categoryName;
            $category->slug = str_slug($categoryName, '-');
            $category->description = $categoryDescription;
            $category->logo = $categoryLogo;
            $category->save();

            return $category;
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Update an existing category Instance
     *
     * @param Emrad\Models\Category $category
     * @param String $categoryName
     * @param String $categoryDescription
     * @param String $categoryLogo
     *
     * @return Emrad\Models\Category
      */

      public function updatecategory(
        Offer $category,
        $categoryName,
        $categoryDescription,
        $categoryLogo
    )
    {
        try {
            $category->name = $categoryName;
            $category->slug = str_slug($categoryName, '-');
            $category->description = $categoryDescription;
            $category->logo = $categoryLogo;
            $category->save();

            return $category;

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
     * return all find category
     *
     * @param \Collection $category
     */
    public function getSingleCategory($slug)
    {
        return $this->offerRepositoryInterface->findBySlug($slug);

    }

    /**
     * return category by name
     *
     * @param \Collection $category
     */
    public function getByName($name)
    {
        return $this->offerRepositoryInterface->getByName($name);
    }
}


