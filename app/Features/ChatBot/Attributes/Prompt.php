<?php

namespace App\Features\ChatBot\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Prompt
{
    /**
     * @param string|array<string> $prompt
     */
    public function __construct(public string|array $prompt)
    {
    }
}