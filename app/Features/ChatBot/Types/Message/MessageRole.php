<?php

namespace App\Features\ChatBot\Types\Message;

enum MessageRole: string
{
    case User = "user";
    case System = "system";
    case Assistant = "assistant";
    case Tool = "tool";
}