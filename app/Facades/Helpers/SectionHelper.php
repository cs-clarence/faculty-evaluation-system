<?php

namespace App\Facades\Helpers;

use App\Helpers\SectionHelper as SectionHelperAccessor;
use Illuminate\Support\Facades\Facade;

class SectionHelper extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SectionHelperAccessor::class;
    }
}
