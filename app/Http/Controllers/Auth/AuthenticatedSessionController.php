<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(Request $request): View
    {
        $previousUrl = url()->previous();

        if ($this->isSafePreviousUrl($previousUrl, $request)) {
            $request->session()->put('login.previous_url', $previousUrl);
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Redirect berdasarkan role
        $user = $request->user();

        if ($user->role === 'vendor' && !$user->vendor) {
            return redirect()->intended(route('vendor.register', absolute: false));
        }

        $roleFallback = match ($user->role) {
            'vendor' => route('vendor.dashboard'),
            'admin'  => route('admin.dashboard'),
            default  => route('user.dashboard'),
        };

        return redirect()->intended($this->resolveLoginFallbackUrl($request, $roleFallback));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    private function resolveLoginFallbackUrl(Request $request, string $defaultUrl): string
    {
        $previousUrl = $request->session()->pull('login.previous_url');

        if (is_string($previousUrl) && $this->isSafePreviousUrl($previousUrl, $request)) {
            return $previousUrl;
        }

        return $defaultUrl;
    }

    private function isSafePreviousUrl(?string $url, Request $request): bool
    {
        if (!$url) {
            return false;
        }

        $parts = parse_url($url);

        if (!is_array($parts)) {
            return false;
        }

        $requestHost = $request->getHost();
        $requestScheme = $request->getScheme();
        $urlHost = $parts['host'] ?? $requestHost;
        $urlScheme = $parts['scheme'] ?? $requestScheme;

        if ($urlHost !== $requestHost || $urlScheme !== $requestScheme) {
            return false;
        }

        $path = $parts['path'] ?? '/';

        $disallowed = [
            '/login',
            '/register',
            '/forgot-password',
            '/reset-password',
            '/logout',
        ];

        if (in_array($path, $disallowed, true)) {
            return false;
        }

        $fullCurrent = rtrim($request->fullUrl(), '/');
        $fullPrevious = rtrim($url, '/');

        return $fullPrevious !== $fullCurrent;
    }
}
