<?php

namespace Emrad\Facade;

use Illuminate\Support\Facades\Facade;

class RoleFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'fc-role-services';
    }
}

