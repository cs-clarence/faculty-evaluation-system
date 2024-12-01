<?php

namespace App\Features\ChatBot\Services;

use App\Features\ChatBot\Agents\MultiToolAgent;
use App\Features\ChatBot\Contracts\Agent;
use App\Features\ChatBot\Contracts\ChatCompletionDriver;
use App\Features\ChatBot\Contracts\ToolCallingDriver;
use App\Features\ChatBot\Types\CompletionResponse\StreamResponse;
use App\Features\ChatBot\Types\CompletionResponse\TextResponse;
use App\Features\ChatBot\Types\Message\Message;
use App\Features\ChatBot\Types\ToolSet;
use Exception;
use Iterator;
use ReflectionException;

readonly class ChatCompleter
{
    private Agent $agent;

    /**
     * @throws ReflectionException
     */
    public function __construct(
        ChatCompletionDriver $driver,
        ToolCallingDriver $toolCaller
    ) {
        $toolSet = new ToolSet();

        $this->agent = new MultiToolAgent($toolSet, $driver, $toolCaller);
    }

    /**
     * @param Message[] $messages
     * @param bool $stream
     * @return TextResponse|StreamResponse
     * @throws Exception
     */
    public function complete(
        array $messages,
        bool $stream = false,
        array $options = [],
    ): TextResponse | StreamResponse {
        return $stream
        ? $this->agent->streamCompletion($messages, $options)
        : $this->agent->getCompletion($messages, $options);
    }

    public function oneShot(
        string $message,
        bool $stream = false,
        array $options = [],
    ): string | Iterator {
        return $stream
        ? $this->agent->streamOneShotCompletion($message, $options)
        : $this->agent->getOneShotCompletion($message, $options);
    }
}
