<?php

namespace App\Engine;

use App\Data\ContextData;
use App\Engine\Value\Action;
use App\Engine\Value\Date;
use App\Engine\Value\Model;
use App\Engine\Value\User;
use DomainException;

/**
 * Class Value
 *
 * Handles the resolution of different value types within the application context.
 * Supports various value types including actions, dates, model attributes, and user attributes.
 */
class Value
{
    /**
     * Resolve a value configuration within the given context.
     *
     * This method determines the appropriate resolver for the given value type
     * and processes the value accordingly.
     *
     * @param  ContextData  $context  The context data containing model and environment information
     * @param  array<string, mixed>|mixed  $config  The value configuration containing type and value
     * @return mixed The resolved value
     *
     * @throws DomainException When value type is missing or invalid
     */
    public static function resolve(ContextData $context, mixed $config): mixed
    {
        $type = $config['type'] ?? null;

        if (! $type) {
            throw new DomainException('Value type is required');
        }

        $value = $config['value'] ?? null;

        if ($type === 'action') {
            return Action::resolve($context, $value);
        }

        if (is_array($value)) {
            $value = Input::resolve($context, $value);
        }

        return match ($type) {
            'date' => Date::resolve($context, $value),
            'model' => Model::resolve($context, $value),
            'user' => User::resolve($context, $value),
            'value' => $value,
            default => throw new DomainException("Invalid value type: {$type}"),
        };
    }
}
