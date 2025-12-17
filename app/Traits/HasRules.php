<?php

namespace App\Traits;

use App\Models\Rule;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait HasRules
 *
 * Provides rule-based functionality to models.
 * Allows models to have associated rules that can be executed on various model events.
 */
trait HasRules
{
    /**
     * The events that can trigger rule execution.
     *
     * @var array<string>
     */
    protected static array $engineEvents = [
        'created',
        'creating',
        'retrieved',
        'updating',
        'updated',
        'deleting',
        'deleted',
    ];

    /**
     * Boot the trait.
     */
    public static function bootHasRules(): void
    {
        foreach (static::getEngineEvents() as $event) {
            static::$event(fn ($model) => $model->applyRules($event));
        }
    }

    /**
     * Get the events that can trigger rule execution.
     *
     * @return array<string>
     */
    public static function getEngineEvents(): array
    {
        return static::$engineEvents;
    }

    /**
     * Apply rules for the given event.
     *
     * @param  string  $event  The event name
     */
    public function applyRules(string $event): void
    {
        $this
            ->rules()
            ->where('event', $event)
            ->get()
            ->filter(fn (Rule $rule) => $rule->validate($this))
            ->each(fn (Rule $rule) => $rule->apply($this));
    }

    /**
     * Get the rules associated with the model.
     *
     * @return Builder<Rule>
     */
    public function rules(): Builder
    {
        return Rule::query()
            ->orderBy('priority', 'desc')
            ->where(fn ($query) => $query
                ->where(fn ($query) => $query->where('model_type', static::class)->where('model_id', $this->id))
                ->orWhere(fn ($query) => $query->where('model_type', static::class)->whereNull('model_id'))
                ->orWhere(fn ($query) => $query->whereNull('model_type')->whereNull('model_id'))
            );
    }

    /**
     * Get a dynamic property from the model.
     *
     * @param  string  $key  The property name
     * @return mixed The property value
     */
    public function __get($key)
    {
        if ($key === 'rules') {
            return $this->rules()->get();
        }

        return parent::__get($key);
    }
}
