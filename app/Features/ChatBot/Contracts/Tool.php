<?php

namespace App\Features\ChatBot\Contracts;


use JsonSerializable;

/**
 * @template TData of mixed
 */
interface Tool extends JsonSerializable
{
    public function getName(): string;

    public function getDescription(): string;

    public function getType(): string;

    /**
     * @param ChatCompletionDriver $driver
     * @param array<string> $messages
     * @param TData|null $data
     * @return string|null
     */
    public function use(ChatCompletionDriver $driver, array $messages, mixed $data = null): ?string;
}