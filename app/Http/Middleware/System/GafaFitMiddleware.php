<?php

namespace App\Http\Middleware\System;

use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Settings\LibSettings;
use Closure;
use View;

class GafaFitMiddleware
{
    /**
     * Handle an incoming request.
     * Si el usuario no tiene acceso a gafafit no puede acceder a este apartado
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if (LibPermissions::userCannotAccessTo($user)) {
            return redirect()->route('admin.home');
        }
        View::share('settings', new LibSettings());

        return $next($request);
    }
}
