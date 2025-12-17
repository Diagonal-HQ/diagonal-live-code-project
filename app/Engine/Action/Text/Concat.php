<?php

namespace App\Engine\Action\Text;

use App\Data\ContextData;
use App\Engine\Action\Action;

/**
 * Class Concat
 *
 * Action to concatenate text values.
 */
class Concat extends Action
{
    /**
     * Execute the text concatenation action.
     *
     * @param  ContextData  $context  The context data containing model and environment information
     * @param  array<string, mixed>  $input  The input data containing text and addition values
     * @return string The concatenated text
     */
    public static function execute(ContextData $context, array $input = []): string
    {
        $text = $input['text'] ?? '';
        $addition = $input['addition'] ?? '';

        return $text.$addition;
    }
}
