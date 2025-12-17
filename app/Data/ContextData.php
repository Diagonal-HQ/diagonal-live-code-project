<?php

namespace App\Data;

use App\Models\Rule;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\LaravelData\Data;

/**
 * Class ContextData
 *
 * Represents the context data used throughout the application for executing actions and rules.
 * Contains references to the current model, rule, and user.
 */
class ContextData extends Data
{
    /**
     * Create a new context data instance.
     *
     * @param  Model  $model  The model instance being operated on
     * @param  Rule  $rule  The rule being executed
     * @param  User|null  $user  The authenticated user, if any
     */
    public function __construct(
        public Model $model,
        public Rule $rule,
        public ?User $user,
    ) {}
}
