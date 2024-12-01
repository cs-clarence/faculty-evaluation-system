<?php

namespace App\Facades\Services;

use App\Services\StudentService as Service;
use Illuminate\Support\Facades\Facade;

class StudentService extends Facade
{
    public static function getFacadeAccessor()
    {
        return Service::class;
    }
}
