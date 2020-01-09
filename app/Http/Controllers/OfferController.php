<?php

namespace Emrad\Http\Controllers;

use Emrad\Models\Offer;
use Illuminate\Http\Request;
use Emrad\Filters\OfferFilters;
use Emrad\Services\FilesServices;
use Emrad\Services\OffersServices;
use Emrad\Http\Requests\CreateOffer;
use Emrad\Http\Resources\OfferResource;
use Emrad\Http\Resources\OfferCollection;
use Symfony\Component\Console\Input\Input;

class OfferController extends Controller
{
    /**
     * @var OffersServices $offersServices
     */
    public $offersServices;

    /**
     * @var filesServices $filesServices
     */
    public $filesServices;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(OffersServices $offersServices, FilesServices $filesServices)
    {

        $this->filesServices = $filesServices;
        $this->offersServices = $offersServices;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOffers(OfferFilters $filters)
    {
        // filters base on the resquest parameters
        $offers = Offer::filter($filters)->orderBy('id', 'desc')->paginate(16);
        return new OfferCollection($offers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createOffer(CreateOffer $request)
    {
        $pathToFile = $this->filesServices->uploadBase64($request->image, 's3');

        $offers = $this->offersServices->createOffer(
                                                        $request->productId,
                                                        $request->offerTitle,
                                                        $pathToFile,
                                                        $request->offerDescription,
                                                        $request->offerStartDate,
                                                        $request->offerEndDate
                                                    );
        return response([
            'status' => 'success',
            'message' => 'offers created successfully',
            'data' => new OfferResource($offers)
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateOffer(Request $request, Offer $offer)
    {
        $pathToFile = $this->filesServices->uploadBase64($request->image, 's3');

        $offers = $this->offersServices->updateOffer(
                                                        $offer,
                                                        $request->productId,
                                                        $request->offerTitle,
                                                        $pathToFile,
                                                        $request->offerDescription,
                                                        $request->offerStartDate,
                                                        $request->offerEndDate
                                                    );
        return response([
            'status' => 'success',
            'message' => 'offers created successfully',
            'data' => new OfferResource($offers)
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Emrad\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function getSingleOffer(Offer $offer)
    {
        $offer = $this->offersServices->getSingleOffer($offer);

        return response([
            'status' => 'success',
            'message' => 'Offer detail',
            'data' => new OfferResource($offer)
        ], 200);
    }
}
