<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 11/04/2018
 * Time: 09:52 AM
 */

namespace App\Librerias\Catalog\Tables\Company;


use App\Admin;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use Illuminate\Http\Request;


class CatalogAdmin extends \App\Librerias\Catalog\Tables\GafaFit\CatalogAdmin
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function administrator()
    {
        return $this->belongsTo(Admin::class, 'id');
    }

    static protected function filtrarQueries(&$query)
    {
        $filters = (array)(request()->get('filters', []));
        $new_array = [];
        foreach ($filters as $k => $v) {
            array_set($new_array, $v['name'], (int)$v['value']);
        }

        $query->with([
            'administrator',
            'profile',
            'administrator.roles',
        ]);

        $id = LibFilters::getFilterValue('id', \request());
        $brands = LibFilters::getFilterValue('brands', \request(), []);
        $locations = LibFilters::getFilterValue('locations', \request(), []);

        $query->whereHas('administrator', function ($q) use ($id, $brands, $locations) {
            $q->whereHas('roles', function ($q) use ($id, $brands, $locations) {
                $q->where(function ($q) use ($id) {
                    $q->where('assigned_type', Company::class);
                    $q->where('assigned_id', $id);
                });
                if (!!$brands) {
                    $q->orWhere(function ($q) use ($brands) {
                        $q->where('assigned_type', Brand::class);
                        $q->whereIn('assigned_id', $brands);
                    });
                }
                if (!!$locations) {
                    $q->orWhere(function ($q) use ($locations) {
                        $q->where('assigned_type', Location::class);
                        $q->whereIn('assigned_id', $locations);
                    });
                }
            });
        });
    }

    public function Valores(Request $request = null)
    {
        $values = parent::Valores($request);

        $admin = $this;

        foreach ($values as $val) {
            $val->setHiddenInList(true);

            if ($val->getTag() === (__('administrators.Name'))) {
                $val->setHiddenInList(false);
            }
            if ($val->getTag() === (__('gafacompany.Edit'))) {
                $val->setHiddenInList(false);
                $val->setGetValueNameFilter(function ($lib, $value) use ($admin) {
                    return VistasGafaFit::view('admin.company.Administrator.buttons', ['admin' => $admin])->render();
                });
            }
        }

        return $values;
    }

    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.company.Administrator.info');
    }
}
