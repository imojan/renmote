<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     *
     * Usage in routes:
     *   Route::middleware('role:admin')   → only admin
     *   Route::middleware('role:vendor')  → only vendor
     *   Route::middleware('role:user')    → only user
     *
     * Can also combine with auth:
     *   Route::middleware(['auth', 'role:admin'])->group(...)
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Cek apakah user sudah login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Cek apakah role user sesuai dengan parameter
        if ($user->role !== $role) {
            // Redirect ke dashboard sesuai role user (bukan 403)
            return match ($user->role) {
                'admin'  => redirect()->route('admin.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.'),
                'vendor' => redirect()->route('vendor.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.'),
                default  => redirect()->route('user.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.'),
            };
        }

        return $next($request);
    }
}
