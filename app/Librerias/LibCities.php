<?php
/**
 * Created by gafa.
 */
namespace App\Librerias;

use App\Models\Cities;
use App\Models\Countries;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class LibCities extends LibBase
{

    public static function GetAllByCountry(Array $request, $relativeToModel, $country_id)
    {
        $perPage = $request['per_page'] ?: 15;
        $country = Countries::find($country_id);
        $cities = $relativeToModel::get()->where('country_code', '=', $country->code);

        $data = new LengthAwarePaginator(
            $cities->forPage(1, $perPage), $cities->count(), $perPage, 1
        );

        return $data;
    }

}