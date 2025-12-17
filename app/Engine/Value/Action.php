<?php

namespace App\Engine\Value;

use App\Data\ContextData;

/**
 * Class Action
 *
 * Resolves action values within the application context.
 */
class Action
{
    /**
     * Resolve an action value within the given context.
     *
     * @param  ContextData  $context  The context data containing model and environment information
     * @param  array|null  $config  The action configuration
     * @return mixed The result of the action execution
     */
    public static function resolve(ContextData $context, ?array $config): mixed
    {
        return \App\Engine\Action::execute($context, $config);
    }
}
