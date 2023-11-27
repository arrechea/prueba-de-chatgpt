<?php

namespace App\Http\Middleware;

use App\Admin;
use App\Librerias\Helpers\LibRoute;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param  string|null              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'admin')
    {
        if (!Auth::guard($guard)->check()) {
            return redirect('admin/login');
        }
        /**
         * @var Admin $admin
         */
        $admin = Auth::user();
        if (!$admin->isActive()) {
            abort(403);
        }
        if ($companyProfile = $admin->getForzeProfileInThisCompany()) {
            if (!$companyProfile->isActive()) {
                abort(403);
            }
        }

        return $next($request);
    }
}
