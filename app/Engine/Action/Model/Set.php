<?php

namespace App\Engine\Action\Model;

use App\Data\ContextData;
use App\Engine\Action\Action;
use DomainException;

/**
 * Class Set
 *
 * Action to set a specific field value on a model.
 */
class Set extends Action
{
    /**
     * Execute the set field action on a model.
     *
     * @param  ContextData  $context  The context data containing model and environment information
     * @param  array<string, mixed>  $input  The input data containing field and value
     * @return null Always returns null as this action only modifies the model
     *
     * @throws DomainException When field or value is not provided
     */
    public static function execute(ContextData $context, array $input = []): null
    {
        if (! isset($input['field'])) {
            throw new DomainException('Field is not provided');
        }

        if (! isset($input['value'])) {
            throw new DomainException('Value is not provided');
        }

        $context->model->{$input['field']} = $input['value'];

        return null;
    }
}
