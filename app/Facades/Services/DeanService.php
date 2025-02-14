<?php
namespace App\Facades\Services;

use App\Services\DeanService as Service;
use Illuminate\Support\Facades\Facade;

class DeanService extends Facade
{
    public static function getFacadeAccessor()
    {
        return Service::class;
    }
}
