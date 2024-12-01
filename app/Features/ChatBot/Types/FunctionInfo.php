<?php

namespace App\Features\ChatBot\Types;

use Closure;

class FunctionInfo
{
    function __construct(
        /**
         * @var callable
         */
        public Closure $function,
        public string  $name,
        public string  $description,
        /**
         * @var array<ParameterInfo>
         */
        public array   $parameters = [],
    )
    {
    }
}
