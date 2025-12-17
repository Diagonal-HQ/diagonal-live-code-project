<?php

namespace App\Actions;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Lorisleiva\Actions\Concerns\AsAction;

/**
 * Class StoreLogin
 *
 * Action to handle user login authentication.
 */
class StoreLogin
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
            ->post('/login', static::class)
            ->middleware(['web', 'guest'])
            ->name('login.store');
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
     * Handle the login authentication.
     */
    public function handle(): void
    {
        Auth::login(User::first());
    }

    /**
     * Execute the action as a controller.
     *
     * @return RedirectResponse Redirect to dashboard after login
     */
    public function asController(): RedirectResponse
    {
        $this->handle();

        return redirect('dashboard');
    }
}
