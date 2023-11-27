<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 10/12/2018
 * Time: 11:42
 */

namespace App\Http\Controllers\Api\SpecialText;


use App\Http\Controllers\ApiController;
use App\Http\Requests\ApiRequest as Request;
use App\Librerias\SpecialText\LibSpecialTextCatalogs;
use App\Models\Brand\Brand;
use App\Models\Catalogs\Catalogs;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SpecialTextApiController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $company = $this->getCompany();

        $this->middleware(function ($request, $next) use ($company) {
            $brand = $request->route('brand', null);
            if ($brand) {
                if ($brand->companies_id !== $company->id) {
                    throw new NotFoundHttpException();
                }
            }

            return $next($request);
        });
    }

    public function form(Request $request, Catalogs $catalog, Brand $brand)
    {
        $company = $this->getCompany();
        $section = $request->get('section', null);

        return LibSpecialTextCatalogs::getGroupsWithFields($company, $catalog, $brand, false, $section);
    }

    public function values(Request $request, Catalogs $catalog, int $model, Brand $brand)
    {
        $company = $this->getCompany();
        $section = $request->get('section', null);

        return LibSpecialTextCatalogs::getModelValues($company, $catalog, $model, $brand, $section);
    }
}
