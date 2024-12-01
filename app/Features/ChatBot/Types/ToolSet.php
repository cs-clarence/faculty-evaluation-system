<?php

namespace App\Features\ChatBot\Types;

use App\Features\ChatBot\Contracts\Tool;
use JsonSerializable;

class ToolSet implements JsonSerializable
{
    /**
     * @var array<string, Tool>
     */
    private array $tools = [];

    /**
     * @param array<Tool> $tools
     */
    public function __construct(Tool ...$tools)
    {
        foreach ($tools as $tool) {
            $this->addTool($tool);
        }
    }

    public function addTool(Tool $tool): self
    {
        $name = $tool->getName();
        $this->tools[$name] = $tool;
        return $this;
    }

    /**
     * @return array<Tool>
     */
    public function getTools(): array
    {
        return array_values($this->tools);
    }

    public function getTool(string $name): ?Tool
    {
        return $this->tools[$name] ?? null;
    }

    public function toolExists(string $name): bool
    {
        return array_key_exists($name, $this->tools);
    }

    public function hasTools(): bool
    {
        return count($this->tools) > 0;
    }

    public function jsonSerialize(): array
    {
        $array = [];

        foreach ($this->tools as $tool) {
            $array[] = $tool->jsonSerialize();
        }

        return $array;
    }
}
