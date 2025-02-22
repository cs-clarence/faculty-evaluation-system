<?php
namespace App\Facades\Services;

use App\Services\FileNameService as Service;
use Illuminate\Support\Facades\Facade;

class FileNameService extends Facade
{
    public static function getFacadeAccessor()
    {
        return Service::class;
    }
}
