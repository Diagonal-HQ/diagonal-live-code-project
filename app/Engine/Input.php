<?php

namespace App\Engine;

use App\Data\ContextData;

/**
 * Class Input
 *
 * Handles the resolution and processing of input data within the application context.
 */
class Input
{
    /**
     * Resolve input values within the given context.
     *
     * This method processes an array of input values, resolving any nested arrays
     * through the Value resolver while preserving scalar values.
     *
     * @param  ContextData  $context  The context data containing model and environment information
     * @param  array<string, mixed>  $input  The input data to resolve
     * @return array<string, mixed> The resolved input data
     */
    public static function resolve(ContextData $context, array $input = []): array
    {
        $result = [];

        foreach ($input as $key => $value) {
            $result[$key] = is_array($value) ? Value::resolve($context, $value) : $value;
        }

        return $result;
    }
}
