<?php

namespace App\Facades\Services;

use App\Services\TeacherService as Service;
use Illuminate\Support\Facades\Facade;

class TeacherService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Service::class;
    }
}
