<?php

namespace App\Features\ChatBot\Abstractions;

use App\Features\ChatBot\Contracts\FunctionGroup;
use App\Features\ChatBot\Tools\FunctionTool\FunctionToolReflector;
use ReflectionException;

abstract class AbstractFunctionGroup implements FunctionGroup
{
    /**
     * @inheritdoc
     * @throws ReflectionException
     */
    public function getFunctionTools(): array
    {
        return FunctionToolReflector::reflect($this);
    }
}