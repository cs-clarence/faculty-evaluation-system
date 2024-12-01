<?php

namespace App\Features\ChatBot\Agents;

use App\Features\ChatBot\Abstractions\AbstractAgent;
use App\Features\ChatBot\Contracts\Agent;
use App\Features\ChatBot\Contracts\ChatCompletionDriver;
use App\Features\ChatBot\Contracts\ToolCallingDriver;
use App\Features\ChatBot\Types\CompletionResponse\StreamResponse;
use App\Features\ChatBot\Types\CompletionResponse\TextResponse;
use App\Features\ChatBot\Types\Message\Message;
use App\Features\ChatBot\Types\ToolSet;
use Exception;

class MultiToolAgent extends AbstractAgent implements Agent
{
    public function __construct(private ToolSet $toolSet, private ChatCompletionDriver $chat, private ToolCallingDriver $toolCaller)
    {
    }

    private function getSystemPromptWithTools(): string
    {
        $prompt = <<<TXT
        You are helpful assistant.
        You have been provided with data from multiple sources and you can use that data to answer the question.
        The data can be in different formats, such as JSON, CSV, or text.
        Don't mention the data format or any technical details to the user like the function called or the data used.
        TXT;

        return $prompt;
    }

    /**
     * @param Message[] $messages
     * @throws Exception
     */
    private function streamOrGetCompletion(array $messages, bool $stream, array $options = []): TextResponse | StreamResponse
    {
        if ($this->toolSet->hasTools()) {
            $toolCalls = $this->toolCaller->getToolCalls($messages, $this->toolSet);

            if ($toolCalls->hasToolCalls()) {
                $messages = $toolCalls->getMessages();
                $contextItems = [];
                foreach ($toolCalls->get() as $toolCall) {
                    $tool = match ($toolCall->type) {
                        "function" => $this->toolSet->getTool(
                            $toolCall->function->name
                        ),
                        default => throw new Exception("Invalid tool call type"),
                    };
                    $context = "";

                    $context .=
                    $tool->use($this->chat, $messages, $toolCall->getData());
                    $contextItems[] = $context;
                }

                $systemPrompt = $this->getSystemPromptWithTools();

                ob_start();
                echo <<<TXT
            {$systemPrompt}\n
            ===================================================\n
            TXT;
                $idx = 1;

                foreach ($contextItems as $context) {
                    echo $idx . ". " . $context . "\n";
                    $idx++;
                }

                $messages[] = Message::system(ob_get_clean());
            }
        }

        return $stream ? $this->chat->streamCompletion($messages, $options) :
        $this->chat->getCompletion($messages, $options);
    }

    /**
     * @throws Exception
     */
    public function getCompletion(array $messages, array $options = []): TextResponse
    {
        return $this->streamOrGetCompletion($messages, false, $options);
    }

    /**
     * @throws Exception
     */
    public function streamCompletion(array $messages, array $options = []): StreamResponse
    {
        return $this->streamOrGetCompletion($messages, true, $options);
    }
}
