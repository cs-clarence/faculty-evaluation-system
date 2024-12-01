<?php

namespace App\Features\ChatBot\Types\ToolCall;

use JsonSerializable;

readonly class FunctionToolCallData implements JsonSerializable
{
    /**
     * @param string $name
     * @param array<string, mixed> $arguments
     */
    public function __construct(
        public string $name,
        public array  $arguments)
    {
    }

    public static function fromArray(array $array): self
    {
        return new self(
            $array['name'],
            json_decode($array['arguments'], true),
        );
    }

    public function jsonSerialize(): array
    {
        return [
            "name" => $this->name,
            "arguments" => json_encode($this->arguments),
        ];
    }
}