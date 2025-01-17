<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $roles)
    {
        // Pastikan user sudah login
        if (!auth()->check()) {
            // return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
            abort(403, 'Unauthorized access');
        }

        $user = auth()->user();

        // Cek apakah user memiliki salah satu role yang diizinkan
        if (!$user->hasAnyRole($roles)) {
            // return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
            abort(403, 'Forbidden access');
        }

        return $next($request);
    }
}
