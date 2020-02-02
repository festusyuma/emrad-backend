<?php

namespace Emrad\Http\Controllers;

use Illuminate\Http\Request;
use Emrad\Http\Requests\MakeRetailerSaleRequest;
use Emrad\Http\Resources\RetailerSaleCollection;
use Emrad\Http\Resources\RetailerSaleResource;
use Emrad\Models\RetailerSale;
use Emrad\Services\SaleServices;
use Emrad\Services\DashboardServices;


class DashboardController extends Controller
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
    public function __construct(DashboardServices $dashboardServices)
    {
        $this->dashboardServices = $dashboardServices;
    }

    /**
     * Display dashboard stats
     */
    public function getDashboardStats()
    {

        try {

            $data = $this->dashboardServices->getDashboardStats(auth()->id());

            return response([
            'status' => 'success',
            'message' => 'Dashboard stats retrieved successfully',
            'data' => $data
            ], 200);

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
