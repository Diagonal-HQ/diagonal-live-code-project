<?php

namespace App\Actions;

use App\Data\TaskData;
use App\Data\UserData;
use App\Models\Rule;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\Concerns\AsAction;

/**
 * Class ShowDashboard
 *
 * Action to display the dashboard page with tasks, rules and user data.
 */
class ShowDashboard
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
            ->get('/dashboard', static::class)
            ->middleware(['web', 'auth'])
            ->name('dashboard');
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
     * Handle the dashboard display.
     *
     * @param  Request  $request  The request instance
     * @return Response The Inertia response with dashboard data
     */
    public function handle(Request $request): Response
    {
        return Inertia::render('Dashboard', [
            'tasks' => TaskData::collect($request->user()->tasks()->todo()->get()),
            'rules' => Rule::all(),
            'user' => UserData::from($request->user()),
        ]);
    }
}
