<?php

namespace App\Features\ChatBot\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_PARAMETER)]
class Description
{
    public function __construct(public string $description)
    {
    }
}
