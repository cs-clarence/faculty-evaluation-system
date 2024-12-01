<?php

namespace App\Facades\Services;

use App\Services\SchoolYearService as Service;
use Illuminate\Support\Facades\Facade;

class SchoolYearService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Service::class;
    }
}
