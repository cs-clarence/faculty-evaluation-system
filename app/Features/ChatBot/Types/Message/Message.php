<?php

namespace App\Features\ChatBot\Types\Message;

use App\Features\ChatBot\Types\ToolCall\ToolCall;
use Exception;
use JsonSerializable;

abstract class Message implements JsonSerializable
{
    public function __construct(
        public MessageRole $role,
        public string      $content,
        public ?string     $id = null,
    )
    {
    }

    public static function user(string $content): UserMessage
    {
        return new UserMessage($content);
    }

    public static function system(string $content): SystemMessage
    {
        return new SystemMessage($content);
    }

    /**
     * @param string|null $content
     * @param ToolCall[]|null $toolCalls
     * @return AssistantMessage
     */
    public static function assistant(?string $content, ?array $toolCalls = null): AssistantMessage
    {
        return new AssistantMessage($content, $toolCalls);
    }

    public static function tool(string $content, string $toolCallId, string $name): ToolMessage
    {
        return new ToolMessage($content, $toolCallId, $name);
    }

    /**
     * @throws Exception
     */
    public static function fromArray(array $data): Message
    {
        if (array_key_exists("role", $data) && is_string($data["role"])) {
            $role = MessageRole::from($data["role"]);
            return match ($role) {
                MessageRole::User => UserMessage::fromArray($data),
                MessageRole::System => SystemMessage::fromArray($data),
                MessageRole::Assistant => AssistantMessage::fromArray($data),
                MessageRole::Tool => ToolMessage::fromArray($data),
            };
        }

        throw new Exception("Invalid message");
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return [
            "role" => $this->role,
            "content" => $this->content,
        ];
    }

    public function is(self $message): bool
    {
        return $this->id === $message->id;
    }
}
