<?php

namespace App\Facades\Services;

use App\Services\UserService as Service;
use Illuminate\Support\Facades\Facade;

class UserService extends Facade
{
    public static function getFacadeAccessor()
    {
        return Service::class;
    }
}
