<?php

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

if (! function_exists('inertia_aware_redirect')) {
    /**
     * Redirect to a given URL with inertia awareness.
     *
     * @param  Request  $request  The request object
     * @param  string  $redirectUrl  The URL to redirect to
     * @return SymfonyResponse|RedirectResponse
     */
    function inertia_aware_redirect(Request $request, string $redirectUrl)
    {
        if ($request->header('x-inertia')) {
            return Inertia::location($redirectUrl);
        }

        return Redirect::to($redirectUrl);
    }
}

if (! function_exists('is_zero')) {
    /**
     * Check if a value is zero.
     *
     * @param  mixed  $value  The value to check
     * @return bool True if the value is zero, false otherwise
     */
    function is_zero(mixed $value): bool
    {
        return bccomp(num1: (string) $value, num2: '0', scale: 10) === 0;
    }
}

if (! function_exists('dispatch_if')) {
    /**
     * Dispatch the given job if the condition is true.
     *
     * @param  bool  $boolean  The condition to check
     * @param  mixed  $job  The job to dispatch
     * @param  array  $parameters  The parameters to pass to the job
     * @return void
     */
    function dispatch_if($boolean, $job, array $parameters = [])
    {
        if ($boolean) {
            dispatch(new $job(...$parameters));
        }
    }
}

if (! function_exists('suppress')) {
    /**
     * Suppress a potential exception and return a default value.
     *
     * @param  callable  $callback  The callback to execute
     * @param  mixed  $rescue  The rescue value to return if an exception occurs
     * @return mixed The result of the callback or the rescue value
     */
    function suppress(callable $callback, $rescue = null)
    {
        return rescue($callback, $rescue, false);
    }
}

if (! function_exists('build_query_string')) {
    /**
     * Build a query string from an array.  Wrapper for http_build_query().
     * Will automatically include or hide the ? mark.
     *
     * @param  array  $params  The array of parameters to build the query string from
     * @return string The built query string
     */
    function build_query_string(array $params)
    {
        $queryString = http_build_query($params);

        return empty($queryString) ? '' : '?'.$queryString;
    }
}

if (! function_exists('url_qs')) {
    /**
     * Url Query String.  Generate a url for the application with query string.
     *
     * @param  string|null  $path  The path to generate the URL for
     * @param  array  $parameters  The query string parameters
     * @param  bool|null  $secure  Whether to generate a secure URL
     * @return string The generated URL with query string
     */
    function url_qs($path = null, $parameters = [], $secure = null)
    {
        $queryString = build_query_string($parameters);

        if (($i = stripos((string) $path, '?')) !== false) {
            $queryString = substr((string) $path, $i).(empty($queryString) ? '' : '&'.substr($queryString, 1));
            $path = substr((string) $path, 0, $i);
        }

        return url($path, [], $secure).$queryString;
    }
}
