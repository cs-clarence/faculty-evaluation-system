<?php

namespace App\Features\ChatBot\Contracts;

use App\Features\ChatBot\Types\Message\Message;

/**
 * @template TCompletion of mixed
 */
interface CompletionResponse
{
    /**
     * @return TCompletion
     */
    public function get(): mixed;

    /**
     * @return Message[]
     */
    public function getMessages(): array;
}