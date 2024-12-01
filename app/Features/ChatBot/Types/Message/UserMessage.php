<?php

namespace App\Features\ChatBot\Types\Message;

use Exception;

class UserMessage extends Message
{
    public function __construct(
        string  $content,
        ?string $id = null,
    )
    {
        parent::__construct(MessageRole::User, $content, $id);
    }

    /**
     * @throws Exception
     */
    public static function fromArray(array $data): Message
    {
        if ($data["role"] !== MessageRole::User->value) {
            throw new Exception("Invalid message role");
        }

        return new UserMessage(
            $data["content"],
            $data["id"] ?? null,
        );
    }
}
