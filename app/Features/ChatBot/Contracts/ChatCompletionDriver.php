<?php

namespace App\Features\ChatBot\Contracts;

use App\Features\ChatBot\Types\CompletionResponse\StreamResponse;
use App\Features\ChatBot\Types\CompletionResponse\TextResponse;
use App\Features\ChatBot\Types\Message\Message;

interface ChatCompletionDriver
{
    /**
     * @param Message[] $messages
     * @return TextResponse
     */
    public function getCompletion(array $messages, array $options = []): TextResponse;

    /**
     * @param Message[] $messages
     * @return StreamResponse
     */
    public function streamCompletion(array $messages, array $options = []): StreamResponse;
}
