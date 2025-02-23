<?php
namespace App\Facades\Services;

use App\Services\FormSubmissionEvaluateeService as Service;
use Illuminate\Support\Facades\Facade;

class FormSubmissionEvaluateeService extends Facade
{
    public static function getFacadeAccessor()
    {
        return Service::class;
    }
}
