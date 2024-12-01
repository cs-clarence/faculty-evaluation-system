<?php

namespace App\Features\ChatBot\Facades;

use Illuminate\Support\Facades\Facade;

class ChatCompleter extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \App\Features\ChatBot\Services\ChatCompleter::class;
    }
}