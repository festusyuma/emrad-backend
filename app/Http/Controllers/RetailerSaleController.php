<?php

namespace Emrad\Http\Controllers;

use Illuminate\Http\Request;
use Emrad\Http\Requests\MakeRetailerSaleRequest;
use Emrad\Http\Resources\RetailerSaleCollection;
use Emrad\Http\Resources\RetailerInventoryCollection;
use Emrad\Http\Resources\RetailerSaleResource;
use Emrad\Models\RetailerSale;
use Emrad\Models\RetailerInventory;
use Emrad\Services\SaleServices;
use Emrad\Filters\InventoryFilters;


class RetailerSaleController extends Controller
{

    /**
     * @var SaleServices $saleServices
     */
    public $saleServices;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SaleServices $saleServices)
    {
        $this->saleServices = $saleServices;
    }


    /**
     * Display a listing of the retailer-sales resource collection.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllRetailerSales()
    {
        $retailerSales = $this->saleServices->getAllRetailerSales(auth()->id(), 10);
        return new RetailerSaleCollection($retailerSales);
    }


    /**
     * Display the specified resource.
     *
     * @param  \Emrad\Models\RetailerSale  $product
     * @return \Illuminate\Http\Response
     */
    public function getSingleRetailerSale($sale_id)
    {
        $retailerSale = $this->saleServices->getSingleRetailerSale($sale_id, auth()->id());

        return response([
            'status' => 'success',
            'message' => 'Sale retrieved succesfully',
            'data' => new RetailerSaleResource($retailerSale)
        ], 200);
    }



    public function makeRetailerSale(MakeRetailerSaleRequest $request)
    {
        $sales = $request->sales;
        $result = $this->saleServices->makeRetailerSale($sales, auth()->id());

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

    public function getInventoryList(InventoryFilters $filters)
    {
        $inventoryList = RetailerInventory::filter($filters)->orderBy('id', 'desc')->paginate(10);
        return new RetailerInventoryCollection($inventoryList);
    }
}
