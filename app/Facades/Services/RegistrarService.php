<?php
namespace App\Facades\Services;

use App\Services\RegistrarService as Service;
use Illuminate\Support\Facades\Facade;

class RegistrarService extends Facade
{
    public static function getFacadeAccessor()
    {
        return Service::class;
    }
}
