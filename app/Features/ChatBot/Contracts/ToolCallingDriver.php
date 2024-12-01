<?php

namespace App\Features\ChatBot\Contracts;

use App\Features\ChatBot\Types\CompletionResponse\ToolCallsResponse;
use App\Features\ChatBot\Types\Message\Message;
use App\Features\ChatBot\Types\ToolSet;

interface ToolCallingDriver
{
    /**
     * @param array<Message> $messages
     * @param ToolSet $toolSet
     * @return ToolCallsResponse
     */
    public function getToolCalls(array $messages, ToolSet $toolSet, array $options = []): ToolCallsResponse;
}
