<?php

namespace App\Actions;

use App\Models\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

/**
 * Class StoreRule
 *
 * Action to store a new rule.
 */
class StoreRule
{
    use AsAction;

    /**
     * Register the routes for this action.
     *
     * @param  Router  $router  The router instance
     */
    public static function routes(Router $router): void
    {
        $router
            ->post('/rules', static::class)
            ->middleware(['web', 'auth']);
    }

    /**
     * Determine if the user is authorized to perform this action.
     *
     * @return bool Whether the user is authorized
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Handle the rule creation.
     *
     * @param  array  $rule  The rule data
     * @return Rule The created rule
     */
    public function handle(array $rule): Rule
    {
        return Rule::create($rule);
    }

    /**
     * Execute the action as a controller.
     *
     * @param  Request  $request  The request instance
     * @return RedirectResponse Redirect back to the previous page
     */
    public function asController(Request $request): RedirectResponse
    {
        $this->handle($request->only([
            'name',
            'model_type',
            'model_id',
            'event',
            'action',
            'guard',
            'priority',
        ]));

        return redirect()->back();
    }

    /**
     * Validation rules for the action.
     *
     * @return array<string, array<int, string>> The validation rules
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'model_type' => ['nullable', 'string', 'max:255'],
            'model_id' => ['nullable', 'uuid'],
            'event' => ['required', 'string'],
            'action' => ['required', 'array'],
            'guard' => ['nullable', 'array'],
            'priority' => ['nullable', 'integer'],
        ];
    }
}
