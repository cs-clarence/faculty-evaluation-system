<?php

namespace App\Features\ChatBot\Types;

class ParameterInfo
{
    function __construct(
        public string $name,
        public string $type,
        public string $description,
        public bool   $required = true,
    )
    {
    }
}
