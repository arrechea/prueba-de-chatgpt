<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 17/07/2018
 * Time: 01:36 PM
 */

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;

class AuthCompany
{
    public function handle(Request $request, Closure $next)
    {
        if ((int)auth()->user()->token()->client->companies_id !== (int)$request->header('gafafit-company')) {
            abort(403);
        }

        return $next($request);
    }
}
