<?php

namespace App\Http\Middleware;

use App\Data\UserData;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Override;

/**
 * Class HandleInertiaRequestsMiddleware
 *
 * Middleware to handle Inertia.js requests with custom shared data.
 */
class HandleInertiaRequestsMiddleware extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     *
     * @param  Request  $request  The request instance
     * @return string|null The asset version
     */
    #[Override]
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  Request  $request  The request instance
     * @return array<string, mixed> The shared props
     */
    #[Override]
    public function share(Request $request): array
    {
        $user = $request->user();

        return array_merge(parent::share($request), [
            'auth' => $user ? [
                'user' => UserData::from($user),
            ] : null,
        ]);
    }
}
