<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use App\Models\Cities;
use App\Models\Countries;
use App\Models\CountryState;

class PlacesApiController extends ApiController
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function countries()
    {
        return response()->json(Countries::all());
    }

    /**
     * @param string $countryCode
     *
     * @return \Illuminate\Http\JsonResponse
     * @internal param Countries $country
     *
     */
    public function cities(string $countryCode)
    {
        return response()->json(Cities::where('country_code', $countryCode)->get());
    }

    /**
     * @param string $countryCode
     *
     * @return \Illuminate\Http\JsonResponse
     * @internal param Countries $country
     *
     */
    public function states(string $countryCode)
    {
        return response()->json(CountryState::where('country_code', $countryCode)->get());
    }

    public function citiesByState(string $countryCode, CountryState $state)
    {
        return $state->cities;
    }
}
