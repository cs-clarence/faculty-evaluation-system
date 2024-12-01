<?php

namespace App\Features\ChatBot\Types\Message;

use App\Features\ChatBot\Types\ToolCall\ToolCall;
use Exception;

class AssistantMessage extends Message
{
    /**
     * @param string $content
     * @param array<ToolCall>|null $toolCalls
     * @param string|null $id
     */
    public function __construct(
        string        $content,
        public ?array $toolCalls,
        ?string       $id = null,
    )
    {
        parent::__construct(MessageRole::Assistant, $content, $id);
    }

    public function jsonSerialize(): array
    {
        $message = parent::jsonSerialize();

        if ($this->toolCalls) {
            $message["tool_calls"] = $this->toolCalls;
        }

        return $message;
    }

    /**
     * @throws Exception
     */
    public static function fromArray(array $data): Message
    {
        if ($data["role"] !== MessageRole::Assistant->value) {
            throw new Exception("Invalid message role");
        }

        return new AssistantMessage(
            $data["content"] ?? "",
            $data["tool_calls"] ?? null,
            $data["id"] ?? null,
        );
    }
}