<?php

namespace App\Http\Controllers\Api\Combo;


use App\Http\Controllers\ApiController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Api\CatalogApiCombo;
use App\Librerias\Responses\LibExpiration;
use App\Models\Brand\Brand;
use App\Http\Requests\ApiRequest as Request;

class CombosApiController extends ApiController
{
    /**
     * @param Request $request
     * @param Brand   $brand
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, Brand $brand)
    {
        return $this->getCombosIndex($request, $brand);
    }

    /**
     * @param Request $request
     * @param Brand   $brand
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userPosibilities(Request $request, Brand $brand)
    {
        return $this->getCombosIndex($request, $brand);
    }

    /**
     * @param Request $request
     * @param Brand   $brand
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function getCombosIndex(Request $request, Brand $brand)
    {
        \request()->merge([
            'filters' => [
                [
                    'name'  => 'brands_id',
                    'value' => $brand->id,
                ],
            ],
        ]);
        $response = CatalogFacade::index($request, CatalogApiCombo::class, $request->get('per_page', null));

        $response = LibExpiration::mapNullExpiration($response);

        return response()->json($response->getRespuestas());
    }
}
