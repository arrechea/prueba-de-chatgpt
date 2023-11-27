<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 08/10/2018
 * Time: 02:00 PM
 */

namespace App\Http\Middleware\ApiMiddleware;


use App\Http\Controllers\ApiController;
use App\Librerias\Helpers\LibRoute;
use App\Models\Brand\Brand;

class ApiBrandMiddleware extends ApiController
{
    public function handle($request, \Closure $next)
    {
        $brand = \request()->route('brand');
        if (!($brand instanceof Brand)) {
            $brand = Brand::where('slug', $brand)->first();
        }
        $company = $this->getCompany();

        if (!$company) {
            abort(404, 'La compañía no existe');
        }
        $brand_ids = $company->brands->pluck('id')->values()->toArray();

        if (!$brand) {
            abort(404);
        }
        if (!in_array($brand->id, $brand_ids)) {
            abort(404);
        }

        return $next($request);
    }
}
