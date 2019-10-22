<?php
namespace Emrad\Services;

use Emrad\Models\Company;
use Emrad\Repositories\Contracts\CompanyRepositoryInterface;

class CompaniesServices
{
    /**
     * @var $companyRepositoryInterface
     */

    public $companyRepositoryInterface;

    public function __construct(CompanyRepositoryInterface $companyRepositoryInterface)
    {
        $this->companyRepositoryInterface = $companyRepositoryInterface;
    }

    /**
     * Create a new company
     *
     * @param Request $request
     *
     * @return \Emrad\Models\Company $company
     */
    public function createCompany($request)
    {
        $company = new Company;
        $company->name = $request->companyName;
        $company->address = $request->companyAddress;
        $company->official_mail = $request->officialMail;
        $company->cac = $request->cac;
        $company->save();

        return $company;
    }

    /**
     * Get all companies
     *
     * @param \Collection $company
     */
    public function getCompanies()
    {
        return $this->companyRepositoryInterface->all();
    }

    /**
     * Get all companies
     *
     * @param \Collection $companyName
     */
    public function findByName($companyName)
    {
        return $this->companyRepositoryInterface->findByName($companyName);
    }


    /**
     * return all find company
     *
     * @param \Collection $product
     */
    public function getSinglecompany($id)
    {
        return $this->companyRepositoryInterface->find($id);
    }

    /**
     * Delete the requested company
     *
     * @param Int|String $id
     *
     * @return void
     */
    public function delete($id)
    {
        $company = $this->companyRepositoryInterface->find($id);

        $company->delete();
    }

    /**
     * Fine the requested company by Id
     * Then Update the company with the $request
     *
     * @param Object $request
     *
     * @param Comapny $company
     *
     * @return \Emrad\Models\Company
     */
    public function updateCompany($request, $company)
    {
        $company->name = $request->companyName;
        $company->address = $request->companyAddress;
        $company->official_mail = $request->officialMail;
        $company->cac = $request->cac;
        $company->save();

        return $company;

    }

}
