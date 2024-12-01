<?php

namespace App\Features\ChatBot\Types\CompletionResponse;


use App\Features\ChatBot\Contracts\CompletionResponse;
use App\Features\ChatBot\Types\Message\Message;
use App\Features\ChatBot\Types\ToolCall\ToolCall;

/**
 * @implements CompletionResponse<ToolCall[]>
 */
class ToolCallsResponse implements CompletionResponse
{
    /**
     * @param ToolCall[] $toolCalls
     * @param Message[] $messages
     */
    public function __construct(
        private array $toolCalls,
        private array $messages = []
    )
    {
    }


    /**
     * @return Message[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @return ToolCall[]
     */
    public function get(): array
    {
        return $this->toolCalls;
    }

    public function hasToolCalls(): bool
    {
        return count($this->toolCalls) > 0;
    }
}