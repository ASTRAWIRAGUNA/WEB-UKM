<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Pastikan pengguna sudah login dan memiliki peran yang sesuai
        if (Auth::check() && Auth::user()->role === $role) {
            return $next($request);
        }

        // Redirect jika tidak memiliki akses
        return redirect('/login')->withErrors('Anda tidak memiliki akses.');
    }
}
