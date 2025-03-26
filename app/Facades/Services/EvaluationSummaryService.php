<?php
namespace App\Facades\Services;

use App\Services\EvaluationSummaryService as Service;
use Illuminate\Support\Facades\Facade;

class EvaluationSummaryService extends Facade
{
    public static function getFacadeAccessor()
    {
        return Service::class;
    }
}
