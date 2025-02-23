<?php
namespace App\Facades\Services;

use App\Services\PendingEvaluationsService as Service;
use Illuminate\Support\Facades\Facade;

class PendingEvaluationsService extends Facade
{
    public static function getFacadeAccessor()
    {
        return Service::class;
    }
}
