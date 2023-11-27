<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cities;
use App\Librerias\LibCities;
use Illuminate\Http\Request;

class CitiesController extends Controller
{

    protected $relativeToModel = 'App\Http\Model\Cities';

    /**
     *  Cities data list for select
     */
    public function SelData(Request $request, $country_id)
    {
        $request->request->add(['per_page' => Cities::count()]);
        $item_list = LibCities::GetAllByCountry($request->all(), $this->relativeToModel, $country_id);
        return json_encode($item_list);
    }

}
