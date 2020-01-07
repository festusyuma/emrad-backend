<?php

namespace Emrad\Http\Controllers;

use Illuminate\Http\Request;
use Emrad\Http\Requests\UpdateRetailerInventoryRequest;
use Emrad\Http\Resources\RetailerInventoryCollection;
use Emrad\Http\Resources\RetailerInventoryResource;
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
        
        $retailerInventories = $this->inventoryServices->getAllRetailerInventories();

        return response([
            'status' => 'success',
            'message' => 'Inventories retrieved succesfully',
            'data' => new RetailerInventoryCollection($retailerInventories)
        ], 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  \Emrad\Models\RetailerInventory  $product
     * @return \Illuminate\Http\Response
     */
    public function getSingleRetailerInventory($inventory_id)
    {
        $retailerInventory = $this->inventoryServices->getSingleRetailerInventory($inventory_id);

        return response([
            'status' => 'success',
            'message' => 'Inventory retrieved succesfully',
            'data' => new RetailerInventoryResource($retailerInventory)
        ], 200);
        
    }


    /**
     * Create new multiple retailer-inventories in database.
     *
     * @param MakeRetailInventory $request
     */
    public function updateRetailerInventory(int $inventory_id, UpdateRetailerInventoryRequest $request)
    {
        $inventorySellingPrice = $request->selling_price;

        $result = $this->inventoryServices->updateRetailerInventory($inventory_id, $inventorySellingPrice);

        return response([
            'status' => 'success',
            'message' => $result
        ], 200);
    }
}
