<?php

namespace Emrad\Http\Controllers;

use Illuminate\Http\Request;
use Emrad\Http\Requests\UpdateRetailerInventoryRequest;
use Emrad\Http\Resources\RetailerInventoryCollection;
use Emrad\Http\Resources\RetailerInventoryResource;
use Emrad\Http\Resources\RetailerProductResource;
use Emrad\Http\Resources\StockHistoryResource;
use Emrad\Models\RetailerInventory;
use Emrad\Services\InventoryServices;


class RetailerInventoryController extends Controller
{

    /**
     * @var InventoryServices $inventoryServices
     */
    public $inventoryServices;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(InventoryServices $InventoryServices)
    {
        $this->inventoryServices = $InventoryServices;
    }


    /**
     * Display a listing of the retailer-inventory resource collection.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllRetailerInventories()
    {
        $retailerInventories = $this->inventoryServices->getAllRetailerInventories(auth()->id(), 10);

        return new RetailerInventoryCollection($retailerInventories);
    }

    public function getSingleRetailerInventory($inventory_id)
    {

            $retailerInventory = $this->inventoryServices->getSingleRetailerInventory($inventory_id, auth()->id());
            return response([
                'status' => 'success',
                'message' => 'Inventory retrieved succesfully',
                'data' => new RetailerInventoryResource($retailerInventory)
        ], 200);
    }


    public function getStockHistory()
    {
        $inventory_id = request()->all()['inventory_id'];
        $stockHistory = $this->inventoryServices->getStockHistory($inventory_id, auth()->id());

        return response([
            'status' => 'success',
            'message' => 'Stock history retrieved succesfully',
            'data' => $stockHistory
            // 'stockHistory' => $stockHistory,
            // 'data' => new StockHistoryResource($stockHistory)
        ], 200);
    }

}
