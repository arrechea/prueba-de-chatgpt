<?php

namespace App\Http\Middleware\System;

use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Settings\LibSettings;
use App\Models\Location\Location;
use Closure;
use View;

class LocationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locationSlug = $request->route('location');

        if ($locationSlug instanceof Location) {
            $location = $locationSlug;
        } else {
            $location = Location::where('slug', $locationSlug)->first();
        }

        $user = auth()->user();

        if (!$location || LibPermissions::userCannotAccessTo($user, $location)) {

            return redirect()->route('admin.home');
        }
//        View::share('settings', new LibSettings());
        View::share('location', $location);

        return $next($request);
    }
}
