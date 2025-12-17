<?php

namespace App\Engine\Value;

use App\Data\ContextData;

/**
 * Class User
 *
 * Resolves user attribute values within the application context.
 */
class User
{
    /**
     * Resolve a user attribute value within the given context.
     *
     * @param  ContextData  $context  The context data containing model and environment information
     * @param  string  $attribute  The attribute name to resolve
     * @return mixed The resolved user attribute value
     */
    public static function resolve(ContextData $context, string $attribute): mixed
    {
        return $context->user->getAttribute($attribute);
    }
}
