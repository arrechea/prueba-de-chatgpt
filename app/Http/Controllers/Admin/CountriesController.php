<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Countries;
use App\Librerias\LibBase;
use Illuminate\Http\Request;

class CountriesController extends Controller
{

    protected $relativeToModel = 'App\Http\Model\Countries';

    /**
     *  Countries data list for select
     */
    public function SelData(Request $request)
    {
        $request->request->add(['per_page' => Countries::count()]);
        $item_list = LibBase::GetAll($request->all(), $this->relativeToModel);
        return json_encode($item_list);
    }

}
