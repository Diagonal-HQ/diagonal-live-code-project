<?php

namespace App\Engine;

use App\Data\ContextData;
use App\Engine\Action\Model\Set;
use App\Engine\Action\Text\Concat;
use DomainException;

/**
 * Class Action
 *
 * Handles the execution of various action types within the application.
 * Maintains a registry of available action types and their corresponding handlers.
 */
class Action
{
    /**
     * Registry of available action types and their corresponding handler classes.
     *
     * @var array<string, class-string>
     */
    public static array $registry = [
        'model.set' => Set::class,
        'text.concat' => Concat::class,
    ];

    /**
     * Execute an action within the given context.
     *
     * @param  ContextData  $context  The context data containing model and environment information
     * @param  array<string, mixed>  $action  The action configuration containing type and input
     * @return mixed The result of the action execution
     *
     * @throws DomainException When action type is missing or invalid
     */
    public static function execute(ContextData $context, array $action): mixed
    {
        $type = $action['type'] ?? null;

        if (! $type) {
            throw new DomainException('Action type is required');
        }

        if (! array_key_exists($type, static::$registry)) {
            throw new DomainException("Invalid action type: {$type}");
        }

        $input = Input::resolve($context, $action['input'] ?? []);

        $action = static::$registry[$type];

        return $action::execute($context, $input);
    }
}
