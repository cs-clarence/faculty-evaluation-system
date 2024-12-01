<?php

namespace App\Features\ChatBot\Helpers;

use App\Features\ChatBot\Types\Message\Message;

class MessagesFilter
{
    /**
     * @param Message[] $messages
     */
    public function __construct(private array $messages)
    {
    }

    /**
     * @return Message[]
     */
    public function get(): array
    {
        return $this->messages;
    }

    /**
     * @param Message $latestSavedMessage
     * @return self
     */
    public function after(Message $latestSavedMessage): self
    {
        $indexOfLatestMessage = false;

        foreach ($this->messages as $index => $message) {
            if ($message->is($latestSavedMessage)) {
                $indexOfLatestMessage = $index;
                break;
            }
        }

        if ($indexOfLatestMessage !== false) {
            $this->messages = array_slice(
                $this->messages,
                $indexOfLatestMessage + 1
            );
        }

        return $this;
    }

    public function exceptLatest(): self
    {
        $n = count($this->messages) - 1;

        $this->messages = array_slice($this->messages, 0, $n);

        return $this;
    }
}
