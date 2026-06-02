<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // cek login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // cek role
        if (Auth::user()->role != $role) {
            abort(403, 'AKSES DITOLAK');
        }

        return $next($request);
    }
}