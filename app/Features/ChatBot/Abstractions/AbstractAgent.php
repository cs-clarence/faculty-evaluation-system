<?php

namespace App\Features\ChatBot\Abstractions;

use App\Features\ChatBot\Contracts\Agent;
use App\Features\ChatBot\Types\Message\Message;
use Iterator;

abstract class AbstractAgent implements Agent
{

    public function getOneShotCompletion(string $message, array $options = []): string
    {
        return $this->getCompletion(
            [
                Message::user($message),
            ]
        )->get();
    }

    public function streamOneShotCompletion(string $message, array $options = []): Iterator
    {
        return $this->streamCompletion(
            [
                Message::user($message),
            ]
        )->get();
    }
}
