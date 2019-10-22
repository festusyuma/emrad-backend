<?php

namespace Emrad\Facade;

use Illuminate\Support\Facades\Facade;

class CompaniesServicesFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'fc-company-services';
    }
}

