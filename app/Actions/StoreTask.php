<?php

namespace App\Actions;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

/**
 * Class StoreTask
 *
 * Action to store a new task.
 */
class StoreTask
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
            ->post('/tasks', static::class)
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
     * Handle the task creation.
     *
     * @param  array  $task  The task data
     * @return Task The created task
     */
    public function handle(array $task): Task
    {
        /** @var User $user */
        $user = Auth::user();

        return $user->tasks()->create($task);
    }

    /**
     * Execute the action as a controller.
     *
     * @param  Request  $request  The request instance
     * @return RedirectResponse Redirect back to the previous page
     */
    public function asController(Request $request): RedirectResponse
    {
        $this->handle($request->only('name', 'due_date'));

        return redirect()->back();
    }

    /**
     * Validation rules for the action.
     *
     * @return array<string, string> The validation rules
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'due_date' => 'nullable|date',
        ];
    }
}
