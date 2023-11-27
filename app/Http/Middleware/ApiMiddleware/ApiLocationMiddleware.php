<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 08/10/2018
 * Time: 03:31 PM
 */

namespace App\Http\Middleware\ApiMiddleware;


use App\Http\Controllers\ApiController;
use App\Librerias\Helpers\LibRoute;
use App\Models\Location\Location;

class ApiLocationMiddleware extends ApiController
{
    public function handle($request, \Closure $next)
    {
        $location = Location::find($request->route('locationToSee'));

        $location_ids = $this->getCompany()->locations->pluck('id')->values()->toArray();

        if (!$location) {
            abort(404);
        }
        if (!in_array($location->id, $location_ids)) {
            abort(404);
        }

        return $next($request);
    }
}
