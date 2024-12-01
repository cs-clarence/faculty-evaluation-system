<?php

namespace App\Features\ChatBot\Types\CompletionResponse;

use App\Features\ChatBot\Contracts\CompletionResponse;
use App\Features\ChatBot\Types\Message\AssistantMessage;
use App\Features\ChatBot\Types\Message\Message;
use InvalidArgumentException;
use Iterator;

/**
 * @implements CompletionResponse<Iterator<string>>
 */
class StreamResponse implements CompletionResponse
{
    /**
     * @param Iterator<string> $stream
     * @param array<Message> $messages
     */
    public function __construct(
        private Iterator $stream,
        private array    $messages = []
    )
    {
        $latestMessage = &$this->messages[count($this->messages) - 1];

        if (!$latestMessage instanceof AssistantMessage ||
            $latestMessage->content !== '') {
            throw new InvalidArgumentException(
                'The last message must be an AssistantMessage with an empty content'
            );
        }
    }

    /**
     * @return Iterator<string>
     */
    public function getStream(): Iterator
    {
        $latestMessage = &$this->messages[count($this->messages) - 1];

        foreach ($this->stream as $message) {
            $latestMessage->content .= $message;
            yield $message;
        }
    }

    /**
     * @return Message[]
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @return Iterator<string>
     */
    public function get(): Iterator
    {
        return $this->getStream();
    }
}