<?php

namespace App\Http\Controllers\Admin\Brand;

use App\Http\Controllers\AdminController;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use Illuminate\Http\Request;

class GraphicsController extends AdminController
{
    /**
     * @param Company $company
     * @param Brand   $brand
     * @param string  $graph
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Company $company, Brand $brand, string $graph)
    {
        $graph = base64_decode($graph);
        $token = \DB::table('oauth_clients')
            ->select('secret')
            ->where('companies_id', $company->id)
            ->first();

        return VistasGafaFit::view('admin.brand.graphics.index', [
            'graph'      => $graph,
            'graphToken' => $token->secret??'',
        ]);
    }
}
