<?php

namespace Emrad\Http\Controllers;

use Illuminate\Http\Request;
use Emrad\Http\Requests\MakeRetailerSaleRequest;
use Emrad\Http\Resources\RetailerSaleCollection;
use Emrad\Http\Resources\RetailerSaleResource;
use Emrad\Models\RetailerSale;
use Emrad\Services\SaleServices;


class RetailerSalesController extends Controller
{

    /**
     * @var SalesServices $saleServices
     */
    public $salesServices;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SalesServices $salesServices)
    {
        $this->salesServices = $salesServices;
    }


    /**
     * Display a listing of the retailer-sales resource collection.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllRetailerSales()
    {
        $retailerSales = $this->salesServices->getAllRetailerSales();

        return response([
            'status' => 'success',
            'message' => 'Sales retrieved successfully',
            'data' => new RetailerSaleCollection($retailerSales)
        ], 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  \Emrad\Models\RetailerSale  $product
     * @return \Illuminate\Http\Response
     */
    public function getSingleRetailerSale($sale_id)
    {
        $retailerSale = $this->saleServices->getSingleRetailerSale($sale_id);

        return response([
            'status' => 'success',
            'message' => 'Sale retrieved succesfully',
            'data' => new RetailerSaleResource($retailerSale)
        ], 200);
    }


    /**
     * Create new multiple retailer-sales in database.
     *
     * @param MakeRetailSale $request
     */
    public function makeRetailerSale(MakeRetailerSaleRequest $request)
    {
        $sales = $request->sales;

        $result = $this->saleServices->makeRetailerSale($sales);

        return response([
            'status' => 'success',
            'message' => $result
        ], 200);
    }

    public function confirmRetailerSale($sale_id)
    {
        $result = $this->saleServices->confirmRetailerSale($sale_id);

        return response([
            'status' => 'success',
            'message' => $result
        ], 200);
    }
}