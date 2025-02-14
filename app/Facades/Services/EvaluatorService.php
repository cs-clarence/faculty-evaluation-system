<?php
namespace App\Facades\Services;

use App\Services\EvaluatorService as Service;
use Illuminate\Support\Facades\Facade;

class EvaluatorService extends Facade
{
    public static function getFacadeAccessor()
    {
        return Service::class;
    }
}
