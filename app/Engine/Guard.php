<?php

namespace App\Engine;

use App\Data\ContextData;
use DomainException;
use Illuminate\Support\Facades\Validator;

/**
 * Class Guard
 *
 * Handles validation of models against rules with dynamic context-aware validation.
 * Provides parsing and validation of rules with support for dynamic options.
 */
class Guard
{
    /**
     * Parse guard rules with context to generate Laravel validation rules.
     *
     * Transforms the guard configuration into a format that Laravel's validator can understand,
     * resolving dynamic values within the given context.
     *
     * @param  ContextData  $context  The context data containing model and environment information
     * @param  array<string, array<int, array<string, mixed>>>  $guard  The guard configuration
     * @return array<string, array<int, string>> The parsed validation rules
     */
    public static function parse(ContextData $context, array $guard): array
    {
        $attributes = [];

        foreach ($guard as $attribute => $rules) {
            $attributes[$attribute] = static::rules($context, $rules);
        }

        return $attributes;
    }

    /**
     * Transform rule configurations into Laravel validation rule strings.
     *
     * Processes each rule configuration and generates the corresponding Laravel
     * validation rule string with optional parameters.
     *
     * @param  ContextData  $context  The context data containing model and environment information
     * @param  array<int, array<string, mixed>>  $rules  The rules configuration
     * @return array<int, string> The transformed validation rules
     *
     * @throws DomainException When rule type is missing
     */
    protected static function rules(ContextData $context, array $rules): array
    {
        return array_map(function ($config) use ($context) {
            $rule = $config['type'] ?? null;

            if (empty($rule)) {
                throw new DomainException('Guard rule type is required');
            }

            if (empty($config['options'])) {
                return $rule;
            }

            $options = static::options($context, $config['options']);

            return "{$rule}:{$options}";
        }, $rules);
    }

    /**
     * Resolve rule options with context data.
     *
     * Transforms an array of rule options into a comma-separated string,
     * resolving each option value within the given context.
     *
     * @param  ContextData  $context  The context data containing model and environment information
     * @param  array<int, mixed>  $options  The options to resolve
     * @return string The resolved options as a comma-separated string
     */
    protected static function options(ContextData $context, array $options): string
    {
        $options = array_map(fn (mixed $option) => Value::resolve($context, $option), $options);

        return implode(',', $options);
    }

    /**
     * Validate a model against guard rules within the given context.
     *
     * Creates a Laravel validator with the model's attributes and the parsed rules,
     * then checks if the validation passes.
     *
     * @param  ContextData  $context  The context data containing model and environment information
     * @param  array<string, array<int, array<string, mixed>>>  $guard  The guard configuration
     * @return bool Whether the validation passes
     */
    public static function validate(ContextData $context, array $guard): bool
    {
        $rules = static::parse($context, $guard);

        $validator = Validator::make($context->model->attributesToArray(), $rules);

        return $validator->passes();
    }
}
