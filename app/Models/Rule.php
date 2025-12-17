<?php

namespace App\Models;

use App\Data\ContextData;
use App\Engine\Action;
use App\Engine\Guard;
use Carbon\Carbon;
use DomainException;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Class Rule
 *
 * Represents a rule that can be applied to models in the application.
 * Rules can be associated with specific models or be global, and can have guards and actions.
 *
 * @property string $id
 * @property string $name
 * @property string|null $model_type
 * @property string|null $model_id
 * @property string $event
 * @property array|null $guard
 * @property array $action
 * @property int|null $priority
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @mixin IdeHelperRule
 */
class Rule extends Model
{
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'model_type',
        'model_id',
        'event',
        'guard',
        'action',
        'priority',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'action' => 'json',
        'guard' => 'json',
    ];

    /**
     * Create a context data instance for this rule and model.
     *
     * Generates a context object containing the model, rule, and current user,
     * which can be used for rule validation and action execution.
     *
     * @param  self  $rule  The rule instance
     * @param  Model  $model  The model instance
     * @return ContextData The created context data
     */
    public static function context(self $rule, Model $model): ContextData
    {
        return ContextData::from([
            'model' => $model,
            'rule' => $rule,
            'user' => Auth::user(),
        ]);
    }

    /**
     * Apply the rule's action to the given model.
     *
     * Executes the rule's configured action within the context of the provided model.
     *
     * @param  Model  $model  The model to apply the action to
     */
    public function apply(Model $model): void
    {
        Action::execute(static::context($this, $model), $this->action);
    }

    /**
     * Validate the rule against the given model.
     *
     * Checks if the rule applies to the model by validating model type and ID,
     * then validates any guard conditions if present.
     *
     * @param  Model  $model  The model to validate against
     * @return bool Whether the validation passed
     *
     * @throws DomainException When model type or ID mismatch occurs
     */
    public function validate(Model $model): bool
    {
        if ($this->model_type && $this->model_type !== $model->getMorphClass()) {
            throw new DomainException('Model type mismatch');
        }

        if ($this->model_id && $this->model_id !== $model->getKey()) {
            throw new DomainException('Model ID mismatch');
        }

        return $this->guard ? Guard::validate(static::context($this, $model), $this->guard) : true;
    }
}
