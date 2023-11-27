<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RejectInactiveAdmins
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     * @throws ValidationException
     */
    public function handle($request, Closure $next)
    {
        /**
         * @var User $admin
         */
        $admin = Auth::user();
        if ($admin) {
            if (!$admin->isActive()) {
                abort(403, __('errors.AdminNotActive.content'));
            }
            if ($companyProfile = $admin->getForzeProfileInThisCompany()) {
                if (!$companyProfile->isActive()) {
                    abort(403, __('errors.AdminNotActive.content'));
                }
            }
        };

        return $next($request);
    }
}
