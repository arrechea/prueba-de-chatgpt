<?php

namespace App\Http\Controllers\Api\Brand;

use App\Http\Controllers\ApiController;
use App\Http\Requests\ApiRequest as Request;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Company\CatalogBrand;
use App\Models\Brand\Brand;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BrandApiController extends ApiController
{
    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        \request()->merge([
            'filters' => [
                [
                    'name'  => 'companies_id',
                    'value' => $this->getCompany()->id??0,
                ],
            ],
        ]);

        $response = CatalogFacade::index($request, CatalogBrand::class, $request->get('per_page', null));

        return response()->json($response->getRespuestas());
    }

    /**
     * @param Request $request
     *
     * @param Brand   $brand
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request, Brand $brand)
    {
        $brand = Brand::where('companies_id', $this->getCompany()->id)
            ->where('status', 'active')
            ->where('id', $brand->id)
            ->first();

        if (!$brand) {
            throw new NotFoundHttpException();
        }

        return response()->json($brand);
    }
}
