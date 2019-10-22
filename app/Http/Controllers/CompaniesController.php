<?php

namespace Emrad\Http\Controllers;

use Emrad\Models\Company;
use Illuminate\Http\Request;
use Emrad\Services\CompaniesServices;
use Emrad\Http\Resources\CompanyResource;

class CompaniesController extends Controller
{

    /**
     * @var $companiesServices
     */

    public $companiesServices;

    public function __construct(CompaniesServices $companiesServices)
    {
        $this->companiesServices = $companiesServices;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCompanies()
    {
        $companies = $this->companiesServices->getCompanies();
        return response()->json([
            'status' => 'success',
            'message' => 'Companies listed successfully ',
            'data' => CompanyResource::collection($companies),
        ]);
    }

    /**
     * Create company
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function createCompany(Request $request)
    {
        $company = $this->companiesServices->createCompany($request);
        return response()->json([
            'status' => 'success',
            'message' => 'Comapny created successfully ',
            'data' => new CompanyResource($company),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param Company $company
     *
     * @return \Illuminate\Http\Response
     */
    public function updateCompany(Request $request, Company $company)
    {
        $company = $this->companiesServices->updateCompany($request, $company);
        return response()->json([
            'status' => 'success',
            'message' => 'Company updated successfully ',
            'data' => new CompanyResource($company),
        ]);
    }

     /**
     * get the single resource.
     *
     * @param  \Emrad\Models\Company  $company
     *
     * @return \Illuminate\Http\Response
     */
    public function getSingleCompany($company)
    {
        $company = $this->companiesServices->getSingleCompany($company);

        return response([
            'status' => 'success',
            'message' => 'company detail',
            'data' => new CompanyResource($company)
        ], 200);
    }
}
