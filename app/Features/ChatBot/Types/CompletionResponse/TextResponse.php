<?php

namespace App\Features\ChatBot\Types\CompletionResponse;

use App\Features\ChatBot\Contracts\CompletionResponse;
use App\Features\ChatBot\Types\Message\Message;

/**
 * @implements CompletionResponse<string>
 */
class TextResponse implements CompletionResponse
{
    /**
     * @param string $text
     * @param array<Message> $messages
     */
    public function __construct(
        private readonly string $text,
        private readonly array $messages = []
    ) {
    }

    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @return Message[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    public function get(): string
    {
        return $this->text;
    }
}
