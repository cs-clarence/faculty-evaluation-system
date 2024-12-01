<?php

namespace App\Features\ChatBot\Contracts;

use App\Features\ChatBot\Tools\FunctionTool\FunctionTool;

interface FunctionGroup
{
    /**
     * Return the list of function tools for this instance.
     * @return FunctionTool[]
     */
    public function getFunctionTools(): array;
}