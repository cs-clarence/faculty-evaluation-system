<?php

namespace App\Features\ChatBot\Types\ToolCall;

/**
 * @extends ToolCall<FunctionToolCallData>
 */
readonly class FunctionToolCall extends ToolCall
{
    public function __construct(
        string                      $id,
        string                      $type,
        public FunctionToolCallData $function,
    )
    {
        parent::__construct($id, $type);
    }

    public static function fromArray(array $array): self
    {
        return new self(
            $array['id'],
            $array['type'],
            FunctionToolCallData::fromArray($array['function']),
        );
    }

    public function jsonSerialize(): array
    {
        return [
            ...parent::jsonSerialize(),
            "function" => $this->function,
        ];
    }

    public function getData(): FunctionToolCallData
    {
        return $this->function;
    }
}