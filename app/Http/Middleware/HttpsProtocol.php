<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;

class HttpsProtocol
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
        $appUrl = Config::get('app.url');

        if (!$request->secure() && strpos($appUrl, 'https', 0) === 0) {
            return redirect()->secure($request->getRequestUri());
        }
        return $next($request);
    }
}
