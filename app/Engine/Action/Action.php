<?php

namespace App\Engine\Action;

use App\Data\ContextData;

/**
 * Abstract Class Action
 *
 * Base class for all actions that can be executed within the application.
 * All action implementations must extend this class and implement the execute method.
 */
abstract class Action
{
    /**
     * Execute the action within the given context with the provided input.
     *
     * @param  ContextData  $context  The context data containing model and environment information
     * @param  array<string, mixed>  $input  The input data for the action
     * @return mixed The result of the action execution
     */
    abstract public static function execute(ContextData $context, array $input = []): mixed;
}
