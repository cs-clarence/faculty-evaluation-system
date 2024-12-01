<?php

namespace App\Features\ChatBot\Tools\FunctionTool;

use App\Features\ChatBot\Attributes\Description;
use InvalidArgumentException;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionParameter;

class FunctionToolReflector
{
    private static function getDescription(
        ReflectionAttribute $attribute
    ): string
    {
        return $attribute->getArguments()[0];
    }

    private static function getJsonSchemaType(string $type): string
    {
        return match ($type) {
            "string" => "string",
            "int" => "integer",
            "float" => "number",
            "bool" => "boolean",
            default => throw new InvalidArgumentException(
                "Unsupported type: $type. Only string, int, float and bool are supported."
            ),
        };
    }

    /**
     * @param mixed $instance
     * @return FunctionTool[]
     * @throws ReflectionException
     */
    public static function reflect(mixed $instance): array
    {
        $reflection = new ReflectionClass($instance);

        $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);
        $tools = [];

        foreach ($methods as $method) {
            $methodName = $method->getName();

            // Skip methods without the FunctionTool attribute
            if (!isset(
                $method->getAttributes(
                    \App\Features\ChatBot\Attributes\FunctionTool::class
                )[0]
            )) {
                continue;
            }

            $descriptionAttr =
                $method->getAttributes(Description::class)[0] ??
                throw new ReflectionException(
                    "Method $methodName does not have a Description attribute."
                );

            $parameters = $method->getParameters();
            $parameters = array_map(
                function (
                    ReflectionParameter $parameter
                ) use ($methodName) {
                    $paramName = $parameter->getName();
                    $descriptionAttr =
                        $parameter->getAttributes(Description::class)[0] ??
                        throw new ReflectionException(
                            "Parameter $paramName of method $methodName does not have a Description attribute."
                        );

                    $allowedPhpTypes = ["string", "int", "float", "bool"];
                    $type = $parameter->getType()?->getName() ?? "string";
                    $required = !$parameter->isDefaultValueAvailable();

                    if (!in_array($type, $allowedPhpTypes)) {
                        throw new ReflectionException(
                            "Parameter $paramName of method $methodName has an unsupported type: $type."
                        );
                    }

                    return [
                        "name" => $paramName,
                        "type" => self::getJsonSchemaType($type),
                        "description" => self::getDescription($descriptionAttr),
                        "required" => $required,
                    ];
                }, $parameters
            );

            $tools[] = new FunctionTool(
                function (mixed ...$args) use ($methodName, $instance) {
                    return $instance->$methodName(...$args);
                },
                $methodName,
                self::getDescription($descriptionAttr),
                $parameters
            );
        }

        return $tools;
    }
}
