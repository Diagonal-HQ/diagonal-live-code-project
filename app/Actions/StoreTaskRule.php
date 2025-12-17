<?php

namespace App\Actions;

use App\Models\Rule;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Lorisleiva\Actions\Concerns\AsAction;
use Override;

/**
 * Class StoreTaskRule
 *
 * Action to store a new rule specifically for tasks.
 * Extends the StoreRule action with task-specific behavior.
 */
class StoreTaskRule extends StoreRule
{
    use AsAction;

    /**
     * Register the routes for this action.
     *
     * @param  Router  $router  The router instance
     */
    #[Override]
    public static function routes(Router $router): void
    {
        $router
            ->post('/tasks/rules', static::class)
            ->middleware(['web', 'auth']);

        $router
            ->post('/tasks/{task}/rules', static::class)
            ->middleware(['web', 'auth']);
    }

    /**
     * Handle the task rule creation.
     *
     * @param  array  $rule  The rule data
     * @param  Task|null  $task  The task to associate the rule with (optional)
     * @return Rule The created rule
     */
    #[Override]
    public function handle(array $rule, ?Task $task = null): Rule
    {
        $rule['model_type'] = Task::class;

        if ($task) {
            $rule['model_id'] = $task->id;
        }

        return parent::handle($rule);
    }

    /**
     * Execute the action as a controller.
     *
     * @param  Request  $request  The request instance
     * @param  Task|null  $task  The task to associate the rule with (optional)
     * @return RedirectResponse Redirect back to the previous page
     */
    #[Override]
    public function asController(Request $request, ?Task $task = null): RedirectResponse
    {
        $rule = $request->only([
            'name',
            'event',
            'action',
            'guard',
            'priority',
        ]);

        $this->handle($rule, $task);

        return redirect()->back();
    }
}
