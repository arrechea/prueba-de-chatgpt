<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 21/08/2018
 * Time: 12:20 PM
 */

namespace App\Librerias\Catalog\Tables\Company;


use App\Admin;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Helpers\LibRoute;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use Illuminate\Http\Request;

class CatalogRol extends \App\Librerias\Catalog\Tables\GafaFit\CatalogRol
{
    public function Valores(Request $request = null)
    {
        $valores = parent::Valores($request);
        $role = $this;
        foreach ($valores as $k => $valor) {
            if ($valor->getTag() === __('roles.Actions')) {
                $valor->setGetValueNameFilter(function ($lib, $value) use ($role) {
                    return VistasGafaFit::view('admin.company.roles.buttons', [
                        'role' => $role,
                    ])->render();
                });
            }
//            if ($valor->getColumna() === 'companies_id') {
//                unset($valores[ $k ]);
//                $valores[] = new LibValoresCatalogo($this, '', 'companies_id', [
//                    'validator'     => 'nullable|exists:companies,id',
//                    'forzeNullable' => true,
//                    'hiddenInList'  => true,
//                ]);
//            }
        }

        $valores = array_values($valores);

        return $valores;
    }

    protected static function filtrarQueries(&$query)
    {
        $request = request();
        $company = LibRoute::getCompany($request);
        $companies_id = $company->id;
        $brand_ids = $company->brands->pluck('id')->toArray();
        $location_ids = $company->locations->pluck('id')->toArray();

        $query->with([
            'company',
            'brand',
            'location',
            'owner',
            'admins' => function ($q) use ($companies_id, $brand_ids, $location_ids) {
                $q->distinct('entity_id');
                $q->where([
                    ['assigned_type', Company::class],
                    ['assigned_id', $companies_id],
                ]);
                $q->orWhere(function ($q) use ($brand_ids) {
                    $q->where('assigned_type', Brand::class);
                    $q->whereIn('assigned_id', $brand_ids);
                });
                $q->orWhere(function ($q) use ($location_ids) {
                    $q->where('assigned_type', Location::class);
                    $q->whereIn('assigned_id', $location_ids);
                });
            },
        ]);

        $brands_id = LibFilters::getFilterValue('brand_filter', $request, null);
        $locations_id = LibFilters::getFilterValue('location_filter', $request, null);

        $query->where('companies_id', $companies_id);
        $query->orWhere(function ($q) {
            $q->whereNull('owner_type');
            $q->whereNull('owner_id');
            $q->whereNull('companies_id');
            $q->whereNull('brands_id');
            $q->whereNull('locations_id');
        });
        if ($brands_id) {
            $query->where('brands_id', $brands_id);
        }
        if ($locations_id) {
            $query->where('locations_id', $locations_id);
        }
    }

    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.company.roles.filters');
    }
}
