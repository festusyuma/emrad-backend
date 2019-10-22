<?php

namespace Emrad\Repositories;

use Emrad\Models\Company;
use Emrad\Repositories\Contracts\CompanyRepositoryInterface;


class CompanyRepository extends BaseRepository implements CompanyRepositoryInterface {

    public $model;

    /**
     * CompanyRepository Constructor
     *
     * @param Emrad\Models\Company $company
      */
    public function __construct(Company $company)
    {
        $this->model = $company;
    }
}
