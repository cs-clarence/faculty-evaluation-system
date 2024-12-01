<?php

namespace App\Features\ChatBot\Contracts;

use App\Features\ChatBot\Types\CompletionResponse\StreamResponse;
use App\Features\ChatBot\Types\CompletionResponse\TextResponse;
use App\Features\ChatBot\Types\Message\Message;
use Iterator;

interface Agent
{
    /**
     * @param Message[] $messages
     * @return TextResponse
     */
    public function getCompletion(array $messages, array $options = []): TextResponse;

    public function getOneShotCompletion(string $message, array $options = []): string;

    /**
     * @param Message[] $messages
     * @return StreamResponse
     */

    public function streamCompletion(array $messages, array $options = []): StreamResponse;

    public function streamOneShotCompletion(string $message, array $options = []): Iterator;
}
