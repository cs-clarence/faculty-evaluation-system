<?php

namespace App\Features\ChatBot\Types\Message;

use Exception;

class SystemMessage extends Message
{
    public function __construct(
        string  $content,
        ?string $id = null,
    )
    {
        parent::__construct(MessageRole::System, $content, $id);
    }

    /**
     * @throws Exception
     */
    public static function fromArray(array $data): Message
    {
        if ($data["role"] !== MessageRole::System->value) {
            throw new Exception("Invalid message role");
        }

        return new SystemMessage(
            $data["content"],
            $data["id"] ?? null,
        );
    }
}
