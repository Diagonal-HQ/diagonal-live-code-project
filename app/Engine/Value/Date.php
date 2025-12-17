<?php

namespace App\Engine\Value;

use App\Data\ContextData;
use Carbon\Carbon;

/**
 * Class Date
 *
 * Resolves date values within the application context.
 * Supports resolving dates with modifications like add and subtract.
 */
class Date
{
    /**
     * Resolve a date value within the given context.
     *
     * @param  ContextData  $context  The context data containing model and environment information
     * @param  array|null  $config  The date configuration with initial, add, subtract, and format options
     * @return string The resolved date in string format
     */
    public static function resolve(ContextData $context, ?array $config): string
    {
        $config ??= [];

        $date = Carbon::parse($config['initial'] ?? null);

        if (! empty($config['add'])) {
            $date->add($config['add']);
        }

        if (! empty($config['subtract'])) {
            $date->subtract($config['subtract']);
        }

        if (! empty($config['format'])) {
            return $date->format($config['format']);
        }

        return (string) $date;
    }
}
