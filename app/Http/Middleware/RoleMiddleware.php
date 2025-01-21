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
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        // Jika role yang diberikan berupa string dan mengandung koma, pecah menjadi array
        $rolesArray = explode('|', $roles);
        // Cek apakah user memiliki salah satu role yang diizinkan
        if (!$user->hasAnyRole($rolesArray)) {
            return redirect()->route('404');
        }

        return $next($request);
    }
}
