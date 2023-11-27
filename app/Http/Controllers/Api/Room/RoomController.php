<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 14/01/2019
 * Time: 09:36
 */

namespace App\Http\Controllers\Api\Room;


use App\Http\Controllers\ApiController;
use App\Http\Requests\ApiRequest as Request;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Brand\CatalogRoom as BrandRoom;
use App\Librerias\Catalog\Tables\Location\CatalogRoom as LocationRoom;
use App\Models\Brand\Brand;
use App\Models\Location\Location;

class RoomController extends ApiController
{
    public function getRoomsInBrand(Request $request, Brand $brand)
    {
        \request()->merge([
            'filters' => [
                [
                    'name'  => 'brands_id',
                    'value' => $brand->id,
                ],
            ],
        ]);

        $response = CatalogFacade::index($request, BrandRoom::class, $request->get('per_page', null));

        return $this->unsetBrand($response);
    }

    public function getRoomsInLocation(Request $request, Brand $brand, Location $location)
    {
        if ($location->brands_id !== $brand->id) {
            abort(404);
        }

        \request()->merge([
            'filters' => [
                [
                    'name'  => 'brands_id',
                    'value' => $brand->id,
                ],
                [
                    'name'  => 'locations_id',
                    'value' => $location->id,
                ],
            ],
        ]);

        $response = CatalogFacade::index($request, LocationRoom::class, $request->get('per_page', null));

        return $this->unsetBrand($response);
    }

    private function unsetBrand($response)
    {
        $json_response = response()->json($response->getRespuestas())->getData();

        array_map(function ($item) {
            unset($item->brand);

            return $item;
        }, $json_response->data);

        return response()->json($json_response);
    }
}
