<?php

namespace App\Features\ChatBot\Tools\FunctionTool;

use App\Features\ChatBot\Contracts\ChatCompletionDriver;
use App\Features\ChatBot\Contracts\FunctionGroup;
use App\Features\ChatBot\Contracts\Tool;
use App\Features\ChatBot\Types\FunctionInfo;
use App\Features\ChatBot\Types\ParameterInfo;
use App\Features\ChatBot\Types\ToolCall\FunctionToolCallData;
use Closure;
use Exception;
use ReflectionException;

/**
 * @extends Tool<FunctionToolCallData>
 */
class FunctionTool implements Tool
{
    public FunctionInfo $info;

    public function __construct(
        Closure $function,
        string  $name,
        string  $description,
        /**
         * @var $parameters array<array>
         */
        array   $parameters
    )
    {
        $this->info = new FunctionInfo(
            $function,
            $name,
            $description,
            array_map(
                function ($param) {
                    return new ParameterInfo(
                        $param["name"],
                        $param["type"],
                        $param["description"],
                        $param["required"] ?? true
                    );
                }, $parameters
            )
        );
    }

    public function getName(): string
    {
        return $this->info->name;
    }

    public function getDescription(): string
    {
        return $this->info->description;
    }

    public function getType(): string
    {
        return "function";
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function use(
        ChatCompletionDriver $driver,
        array                $messages,
        mixed                $data = null
    ): ?string
    {
        if (!$data instanceof FunctionToolCallData) {
            throw new Exception("Invalid data");
        }

        $arguments = $data->arguments;

        ob_start();
        call_user_func_array($this->info->function, $arguments);
        $response = ob_get_clean();

        if (!$response) {
            return null;
        }

        return $response;
    }

    private function getRequiredParameterNames(): array
    {
        $required = [];

        foreach ($this->info->parameters as $param) {
            if ($param->required) {
                $required[] = $param->name;
            }
        }
        return $required;
    }

    private function getParameterProperties(): array
    {
        $properties = [];

        foreach ($this->info->parameters as $param) {
            $properties[$param->name] = [
                "type" => $param->type,
                "description" => $param->description,
            ];
        }

        return $properties;
    }

    public function jsonSerialize(): array
    {
        $json = [
            "type" => $this->getType(),
            "function" => [
                "name" => $this->getName(),
                "description" => $this->getDescription(),
            ],
        ];

        if (count($this->info->parameters) > 0) {
            $json["function"]["parameters"] = [
                "type" => "object",
                "properties" => $this->getParameterProperties(),
                "required" => $this->getRequiredParameterNames(),
            ];
        }

        return $json;
    }

    /**
     * @param mixed ...$instances
     * @return FunctionTool[]
     * @throws ReflectionException
     */
    public static function getTools(mixed ...$instances): array
    {
        $tools = [];

        foreach ($instances as $instance) {
            if ($instance instanceof FunctionGroup) {
                $tools = [...$tools, ...$instance->getFunctionTools()];
            } else {
                $tools =
                    [...$tools, ...FunctionToolReflector::reflect($instance)];
            }
        }

        return $tools;
    }
}
