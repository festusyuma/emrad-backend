<?php

namespace Emrad\Facade;

use Illuminate\Support\Facades\Facade;

class UsersFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'fc-users-repo-interface';
    }
}

