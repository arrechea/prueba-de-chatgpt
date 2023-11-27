<?php

namespace App\Http\Controllers\Api\Location;

use App\Http\Controllers\ApiController;
use App\Http\Requests\ApiRequest as Request;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Brand\CatalogLocation;
use App\Models\Brand\Brand;
use App\Models\Location\Location;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LocationController extends ApiController
{
    /**
     * @param Request $request
     *
     * @param Brand   $brand
     *
     * @return \Illuminate\Http\JsonResponse
     * @internal param Brand $brand
     *
     */
    public function index(Request $request, Brand $brand)
    {
        \request()->merge([
            'filters' => [
                [
                    'name'  => 'brands_id',
                    'value' => $brand->id,
                ],
                [
                    'name'  => 'apiOrder',
                    'value' => 'order',
                ],
                [
                    'name'  => 'date_limitation',
                    'value' => true,
                ],
                [
                    'name'  => 'reducePopulation',
                    'value' => $request->get('reducePopulation', false) === 'true',
                ],
            ],
        ]);
        $response = CatalogFacade::index($request, CatalogLocation::class, $request->get('per_page', null));

        return response()->json($response->getRespuestas());
    }

    /**
     * @param Request $request
     * @param Brand   $brand
     * @param int     $locationToSee
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request, Brand $brand, $locationToSee)
    {
        $now = $brand->now();

        $locationToSee = Location::where('id', $locationToSee)
            ->where(function ($query) use ($now) {
                $query->where('since', '<=', $now);
                $query->orWhereNull('since');
            })
            ->where(function ($query) use ($now) {
                $query->whereNull('until');
                $query->orWhere('until', '>=', $now);
            })
            ->with([
                'country',
            ])
            ->first();

        if (!$locationToSee || $locationToSee->brands_id !== $brand->id) {
            throw new NotFoundHttpException();
        }

        return response()->json($locationToSee);
    }
}
