<?php

namespace App\Features\ChatBot\Drivers;

use App\Features\ChatBot\Contracts\ChatCompletionDriver;
use App\Features\ChatBot\Contracts\ToolCallingDriver;
use App\Features\ChatBot\Types\CompletionResponse\StreamResponse;
use App\Features\ChatBot\Types\CompletionResponse\TextResponse;
use App\Features\ChatBot\Types\CompletionResponse\ToolCallsResponse;
use App\Features\ChatBot\Types\Message\Message;
use App\Features\ChatBot\Types\ToolCall\ToolCall;
use App\Features\ChatBot\Types\ToolSet;
use Exception;
use Iterator;
use LucianoTonet\GroqPHP\Groq;
use LucianoTonet\GroqPHP\Stream;

class GroqDriver implements ChatCompletionDriver, ToolCallingDriver
{
    private string $apiKey;
    private string $model;

    private ?int $maxRetries;
    private ?int $timeout;

    private Groq $groq;

    public function __construct(array $options = [])
    {
        $this->apiKey = $options['api_key'] ?? '';
        $this->model = $options['model'] ?? 'mixtral-8x7b-32768';
        if (isset($options['max_retries'])) {
            $this->maxRetries = $options['max_retries'];
        }
        if (isset($options['timeout'])) {
            $this->timeout = $options['timeout'];
        }
    }

    private function groq()
    {
        if (!isset($this->groq)) {
            $this->groq = new Groq($this->apiKey);
        }
        return $this->groq;
    }

    private function getOptions(): array
    {
        $options = [];

        if (isset($this->maxRetries)) {
            $options['maxRetries'] = $this->maxRetries;
        }
        $options['timeout'] = $this->timeout ?? 10_000;

        return $options;
    }

    /**
     * @param array<Message> $messages
     * @param bool $stream
     * @param ToolSet|null $tools
     * @return array
     */
    private function getParams(array $messages, bool $stream = false, ?ToolSet $tools = null, array $options = []): array
    {
        $params = [
            'model' => $this->model,
            'messages' => array_map(fn($msg) => $msg->jsonSerialize(), $messages),
            'stream' => $stream,
            ...$options,
        ];

        if (isset($tools)) {
            $params['tools'] = $tools->jsonSerialize();
        }

        return $params;
    }

    private function makeRequest(array $messages, bool $stream = false, ?ToolSet $tools = null, array $options = []): array | Stream
    {
        $params = $this->getParams($messages, $stream, $tools, $options);
        $options = $this->getOptions();

        return $this->groq()->chat()->completions()->create($params, $options);
    }

    /**
     * @inheritdoc
     * @throws Exception
     */
    public function getCompletion(array $messages, array $options = []): TextResponse
    {
        $chatCompletions = $this->makeRequest($messages, false, null, $options);

        $message = $chatCompletions['choices'][0]['message'];

        $messages[] = Message::fromArray($message);

        $message = $message['content'];

        return new TextResponse($message, $messages);
    }

    public function streamCompletion(array $messages, array $options = []): StreamResponse
    {
        $stream = $this->makeRequest($messages, true, null, $options);

        $messages[] = Message::assistant("");

        $stream = self::getIteratorFromStream($stream);

        return new StreamResponse($stream, $messages);
    }

    private static function getIteratorFromStream(Stream $stream): Iterator
    {
        foreach ($stream->chunks() as $chunk) {
            $delta = $chunk['choices'][0]['delta'];

            if (array_key_exists('content', $delta)) {
                yield $delta['content'];
            }
        }
    }

    public function getToolCalls(array $messages, ToolSet $toolSet, array $options = []): ToolCallsResponse
    {
        $response = $this->makeRequest($messages, false, $toolSet, $options);

        $message = $response['choices'][0]['message'];

        if (array_key_exists(
            "tool_calls", $message,
        )) {
            $toolCalls = $message['tool_calls'];

            $toolCalls = array_map(
                /**
                 * @throws Exception
                 */
                function ($toolCall) {
                    return ToolCall::fromArray($toolCall);
                },
                $toolCalls
            );

            $messages[] = Message::assistant("", $toolCalls);

            return new ToolCallsResponse($toolCalls, $messages);
        }

        return new ToolCallsResponse([], $messages);
    }
}
