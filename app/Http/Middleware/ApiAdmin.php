<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class ApiAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->role !== 'admin') {
            return response(json_encode(['error' => Lang::get('auth.Unauthenticated')]), 401)
                ->header('Content-Type', 'text/json');
        }

        return $next($request);
    }
}
