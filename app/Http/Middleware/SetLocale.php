<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Apply the user's preferred locale (from session) to the request.
     * Defaults to the app fallback locale defined in config/app.php.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supported = config('app.supported_locales', ['id', 'en']);
        $sessionLocale = (string) $request->session()->get('locale', '');

        $locale = in_array($sessionLocale, $supported, true)
            ? $sessionLocale
            : (string) config('app.locale', 'id');

        App::setLocale($locale);

        return $next($request);
    }
}
