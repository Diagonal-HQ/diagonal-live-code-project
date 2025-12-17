<?php

namespace App\Engine\Value;

use App\Data\ContextData;

/**
 * Class Model
 *
 * Resolves model attribute values within the application context.
 */
class Model
{
    /**
     * Resolve a model attribute value within the given context.
     *
     * @param  ContextData  $context  The context data containing model and environment information
     * @param  string  $attribute  The attribute name to resolve
     * @return mixed The resolved model attribute value
     */
    public static function resolve(ContextData $context, string $attribute): mixed
    {
        return $context->model->getAttribute($attribute);
    }
}
