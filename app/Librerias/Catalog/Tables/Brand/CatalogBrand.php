<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 23/04/2018
 * Time: 09:40 AM
 */

namespace App\Librerias\Catalog\Tables\Brand;


use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Vistas\VistasGafaFit;
use Illuminate\Http\Request;

class CatalogBrand extends \App\Librerias\Catalog\Tables\Company\CatalogBrand
{
    public function Valores(Request $request = null)
    {
        $request = $request ? $request : \request();
        $arr = parent::Valores($request);
        $gafaBrand = $this;

        foreach ($arr as $k => $column) {
            if ($column->getTag() === __('brand.Actions')) {
                $column->setGetValueNameFilter(function ($lib, $val) {
                    $company = $lib->getModel();
                    $view_route = route('admin.brand.dashboard', ['brand' => $this]);
                    $edit_route = route('admin.brand.settings.index', ['brand' => $this]);
                    $delete_route = route('admin.brand.settings.delete', ['brand' => $this, 'brandToEdit' => $this->id]);

                    return VistasGafaFit::view('admin.brand.brands.buttons', [
                        'company'      => $company,
                        'view_route'   => $view_route,
                        'edit_route'   => $edit_route,
                        'delete_route' => $delete_route,
                        'active'       => $company->isActive(),
                    ])->render();
                });
            }
            if ($column->getColumna() === 'name') {
                unset($arr[ $k ]);
                array_values($arr);
            }
        }

        array_push($arr, new LibValoresCatalogo($this, __('brand.Name'), 'name', [
            'validator' => 'required|string|max:100',
        ], function () use ($gafaBrand, $request) {
            //Extras
            if (
                $request->has('waiver_forze')
                &&
                $request->get('waiver_forze', '') === 'on'
            ) {
                $gafaBrand->waiver_forze = true;
            } else {
                $gafaBrand->waiver_forze = false;
            }

            if (
                $request->has('time_format')
                &&
                $request->get('time_format', '') === 'on'
            ) {
                $gafaBrand->time_format = '24';
            } else {
                $gafaBrand->time_format = '12';
            }

            if (
                $request->has('waitlist')
                &&
                $request->get('waitlist', '') === 'on'
            ) {
                $gafaBrand->waitlist = true;
            } else {
                $gafaBrand->waitlist = false;
            }

        }));

        return $arr;
    }

    static protected function filtrarQueries(&$query)
    {
        parent::filtrarQueries($query);

        $query->where('admins_id', '=', auth()->id());
    }

    public function runLastSave()
    {

    }
}
