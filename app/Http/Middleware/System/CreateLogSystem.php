<?php

namespace App\Http\Middleware\System;

use App\Admin;
use App\Jobs\LogSystem;
use App\Librerias\Helpers\LibRoute;
use App\Models\Log\CompaniesRequests;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateLogSystem
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
        return $next($request);
    }

    /**
     * @param  \Illuminate\Http\Request $request
     * @param                           $response
     */
    public function terminate($request, $response)
    {
//        if (config('app.env') === 'production') {
//            $company = LibRoute::getCompany($request);
//            $user = Auth::user();
//            $userId = ($user ? $user->id : null);
//            $isAdmin = ($user instanceof Admin);
//            $company_id = ($company ? $company->id : null);
//
//            $routeResolver = $request->route();
//            $controller = $routeResolver ? $routeResolver->getActionName() : null;
//            $parameters = $routeResolver ? json_encode($routeResolver->parameters()) : null;
//            $variables = '[]';//$routeResolver ? json_encode($request->all()) : null;
//
//            $logSystem = new LogSystem($userId, $isAdmin, $company_id, $controller, $parameters, $variables);
//            dispatch($logSystem);
//        }
    }
}
