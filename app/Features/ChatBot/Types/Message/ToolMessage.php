<?php

namespace App\Features\ChatBot\Types\Message;

use Exception;

class ToolMessage extends Message
{
    public function __construct(
        string        $content,
        public string $toolCallId,
        public string $name,
        ?string       $id = null,
    )
    {
        parent::__construct(MessageRole::Tool, $content, $id);
    }

    public function jsonSerialize(): array
    {
        return [
            ...parent::jsonSerialize(),
            "tool_call_id" => $this->toolCallId,
            "name" => $this->name,
        ];
    }

    /**
     * @throws Exception
     */
    public static function fromArray(array $data): Message
    {
        if ($data["role"] !== MessageRole::Tool->value) {
            throw new Exception("Invalid message role");
        }

        return new ToolMessage(
            $data["content"],
            $data["tool_call_id"],
            $data["name"],
            $data["id"] ?? null,
        );
    }
}