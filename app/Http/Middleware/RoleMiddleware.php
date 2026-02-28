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
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Cek apakah user sudah login
        if (!auth()->check()) {
            abort(403, 'Unauthorized');
        }

        // Cek apakah role user sesuai dengan parameter
        if (auth()->user()->role !== $role) {
            abort(403, 'Unauthorized - Anda tidak memiliki akses ke halaman ini');
        }

        return $next($request);
    }
}
