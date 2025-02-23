<?php
namespace App\Facades\Services;

use App\Services\FormSubmissionExportService as Service;
use Illuminate\Support\Facades\Facade;

class FormSubmissionExportService extends Facade
{
    public static function getFacadeAccessor()
    {
        return Service::class;
    }
}
