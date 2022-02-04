<?php

namespace Emrad\Facade;

use Illuminate\Support\Facades\Facade;

class UsersServicesFacade extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return 'fc-users-services';
    }
}

