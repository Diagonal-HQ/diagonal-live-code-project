<?php

namespace App\Actions;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Lorisleiva\Actions\Concerns\AsAction;

/**
 * Class ShowLogin
 *
 * Action to display the login page for guests.
 */
class ShowLogin
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
            ->get('/login', static::class)
            ->middleware(['web', 'guest'])
            ->name('login');
    }

    /**
     * Determine if the user is authorized to perform this action.
     *
     * @return bool Whether the user is authorized (only guests)
     */
    public function authorize(): bool
    {
        return ! Auth::check();
    }

    /**
     * Handle the login page display.
     *
     * @return Response The Inertia response with guest page
     */
    public function handle(): Response
    {
        return Inertia::render('Guest');
    }
}
