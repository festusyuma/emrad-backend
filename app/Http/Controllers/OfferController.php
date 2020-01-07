<?php

namespace Emrad\Http\Controllers;

use Emrad\Models\Offer;
use Illuminate\Http\Request;
use Emrad\Filters\OfferFilters;
use Emrad\Services\OffersServices;
use Emrad\Http\Resources\OfferCollection;

class OfferController extends Controller
{
    /**
     * @var OffersServices $offersServices
     */
    public $offersServices;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(OffersServices $offersServices)
    {
        $this->OffersServices = $offersServices;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getOffers(OfferFilters $filters)
    {
        // filters base on the resquest parameters
        $products = Offer::filter($filters)->get();
        return new OfferCollection($products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \Emrad\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function show(Offer $offer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \Emrad\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function edit(Offer $offer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Emrad\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Offer $offer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Emrad\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Offer $offer)
    {
        //
    }
}
