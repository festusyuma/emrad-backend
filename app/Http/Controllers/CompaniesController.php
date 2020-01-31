<?php

namespace Emrad\Http\Controllers;

use Emrad\Models\Company;
use Illuminate\Http\Request;
use Emrad\Filters\CompanyFilters;
use Emrad\Services\CompaniesServices;
use Emrad\Http\Requests\CreateCompany;
use Emrad\Http\Resources\CompanyResource;
use Emrad\Http\Resources\CompaniesCollection;

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
    public function getCompanies(CompanyFilters $filters)
    {
        // filters base on the resquest parameters
        $companies = Company::filter($filters)->get();

        return new CompaniesCollection($companies);
    }

    /**
     * Create company
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function createCompany(CreateCompany $request)
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
    public function updateCompany(CreateCompany $request, Company $company)
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
