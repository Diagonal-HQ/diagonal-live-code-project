<?php

namespace App\Actions;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

/**
 * Class CompleteTask
 *
 * Action to mark a task as completed.
 */
class CompleteTask
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
            ->post('/tasks/{task}/complete', static::class)
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
     * Handle the task completion.
     *
     * @param  Task  $task  The task to mark as completed
     */
    public function handle(Task $task): void
    {
        $task->update(['completed_at' => now()]);
    }

    /**
     * Execute the action as a controller.
     *
     * @param  Task  $task  The task to mark as completed
     * @return RedirectResponse Redirect back to the previous page
     */
    public function asController(Task $task): RedirectResponse
    {
        $this->handle($task);

        return redirect()->back();
    }
}
