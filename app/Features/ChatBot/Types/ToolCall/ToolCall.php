<?php

namespace App\Features\ChatBot\Types\ToolCall;

use Exception;
use JsonSerializable;

/**
 * @template TData of mixed
 */
readonly abstract class ToolCall implements JsonSerializable
{
    public function __construct(
        public string $id,
        public string $type,
    )
    {
    }

    /**
     * @throws Exception
     */
    public static function fromArray(array $array): self
    {
        $type = $array['type'];

        return match ($type) {
            "function" => FunctionToolCall::fromArray($array),
            default => throw new Exception("Invalid tool call type"),
        };
    }

    /**
     * @return TData
     */
    public abstract function getData(): mixed;

    public function jsonSerialize(): array
    {
        return [
            "id" => $this->id,
            "type" => $this->type,
        ];
    }
}