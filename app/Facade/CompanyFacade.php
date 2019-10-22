<?php

namespace Emrad\Facade;

use Illuminate\Support\Facades\Facade;

class CompanyFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'fc-company-repo-interface';
    }
}

