<?php
namespace App\Facades\Services;

use App\Services\HumanResourcesStaffService as Service;
use Illuminate\Support\Facades\Facade;

class HumanResourcesStaffService extends Facade
{
    public static function getFacadeAccessor()
    {
        return Service::class;
    }
}
