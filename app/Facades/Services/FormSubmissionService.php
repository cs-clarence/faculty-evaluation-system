<?php

namespace App\Facades\Services;

use App\Services\FormSubmissionService as Service;
use Illuminate\Support\Facades\Facade;

class FormSubmissionService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Service::class;
    }
}
