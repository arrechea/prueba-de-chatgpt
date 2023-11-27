<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 11/04/2018
 * Time: 06:06 PM
 */

namespace App\Librerias\Catalog\Tables\Company;


use App\AssignedRol;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use Illuminate\Http\Request;

class CatalogAdminProfile extends \App\Librerias\Catalog\Tables\GafaFit\CatalogAdminProfile
{
    /**
     * Regresa las columnas a mostrar en la tabla del listado y sus validadores.
     *
     * @param Request|null $request
     *
     * @return LibValoresCatalogo[]
     */
    public function Valores(Request $request = null)
    {
        $values = parent::Valores($request);

        $profile = $this;

        $companies_id = LibFilters::getFilterValue('id');

        foreach ($values as $val) {
            $val->setHiddenInList(true);

            if ($val->getColumna() == 'email') {
                $val->setHiddenInList(false);
            }
            if ($val->getTag() === (__('administrators.Name'))) {
                $val->setHiddenInList(false);
            }
            if ($val->getColumna() === 'pic') {
                $val->setHiddenInList(false);
            }
            if ($val->getTag() === 'Roles') {
                $val->setHiddenInList(false);
                $val->setGetValueNameFilter(function ($lib) use ($profile, $companies_id) {
                    $roles = $profile->admin->roles->unique('id');

                    return VistasGafaFit::view('admin.company.Administrator.roles', [
                        'roles' => $roles,
                    ])->render();
                });
            }
            //Seteo de los botone sde nivel compañía
            if ($val->getTag() === (__('gafacompany.Edit'))) {
                $val->setHiddenInList(false);
                $val->setGetValueNameFilter(function ($lib, $value) use ($profile) {
                    return VistasGafaFit::view('admin.company.Administrator.buttons', ['admin' => $profile])->render();
                });
            }

            if($val->getTag()===__('users.Active')){
                $val->setHiddenInList(false);
            }
        }


        array_push($values, new LibValoresCatalogo($this, '', 'companies_id', [
            'validator'    => 'required|int|min:0',
            'hiddenInList' => true,
        ]));

        return $values;
    }

    /**
     * Filtra los administradores para regresar sólo los que tengan roles asociados con
     * la compañía o alguna de las marcas o ubicaciones asociadas a ésta.
     *
     * @param $query
     */
    static protected function filtrarQueries(&$query)
    {
        $id = LibFilters::getFilterValue('id', \request());

        $company = Company::find($id);
        $brands = $company->brands->pluck('id')->values()->toArray();
        $locations = $company->locations->pluck('id')->values()->toArray();

        //Obtiene las relaciones a utilizar
        $query->with([
            'admin',
            'admin.roles' => function ($q) use ($brands, $locations, $company) {
                $q->where([
                    ['assigned_type', Company::class],
                    ['assigned_id', $company->id],
                ]);
                $q->orWhere(function ($q) use ($brands) {
                    $q->where('assigned_type', Brand::class);
                    $q->whereIn('assigned_id', $brands);
                });
                $q->orWhere(function ($q) use ($locations) {
                    $q->where('assigned_type', Location::class);
                    $q->whereIn('assigned_id', $locations);
                });
                $q->orWhereNull('assigned_type');
            },
        ]);

        $query->where('companies_id', $id);

        $query->selectRaw('*,concat(first_name," ",last_name) full_name');

        /*
         * Filtra los admins con roles con 'assigned_type' company y con 'assigned_id' igual
         * al de la compañía actual (obtenido en el filtro), o bien con tipo 'brand' o 'location'
         * y id's que pertenezcan a marcas o ubicaciones asociadas con la compañia.
         */
//        $query->whereHas('admin', function ($q) use ($new_array) {
//            $q->whereHas('roles', function ($q) use ($new_array) {
//                $q->where(function ($q) use ($new_array) {
//                    $q->where('assigned_type', Company::class);
//                    $q->where('assigned_id', isset($new_array['id']) ? $new_array['id'] : 0);
//                });
//                if (isset($new_array['brands'])) {
//                    $q->orWhere(function ($q) use ($new_array) {
//                        $q->where('assigned_type', Brand::class);
//                        $q->whereIn('assigned_id', $new_array['brands']);
//                    });
//                }
//                if (isset($new_array['locations'])) {
//                    $q->orWhere(function ($q) use ($new_array) {
//                        $q->where('assigned_type', Location::class);
//                        $q->whereIn('assigned_id', $new_array['locations']);
//                    });
//                }
//            });
//        });
    }

    /**
     * Regresa la vista de info que contiene la información de la compañía y las
     * marcas y ubicaciones asociadas para poder utilizarlas en el filtro
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.company.Administrator.info');
    }

    /**
     * Añade una validación que comprueba que la compañía actual y el 'companies_id' que
     * se envía desde el formulario coincidan.
     *
     * @param $validator
     */
    public function extenderValidacion(&$validator)
    {
        parent::extenderValidacion($validator);

        $request = request();

        /*
         * Validar Compañía
         */
        $companies_id = $request->get('companies_id', null);
        $company = $request->get('current_company', null);
        if ((int)$companies_id !== $company->id) {
            $validator->errors()->add('companies_id', __('validation.same', [
                'attribute' => __('company.CompanyID'),
                'other'     => __('company.CurrentCompany'),
            ]));
        }
    }

    protected static function ColumnToSearch()
    {
        $companies_id = LibFilters::getFilterValue('id', \request());

        return function ($query, $criterio) use ($companies_id) {
            $query->where(function ($q) use ($criterio) {
                $q->where('first_name', 'like', "%{$criterio}%");
                $q->orWhere('last_name', 'like', "%{$criterio}%");
                $q->orWhere('email', 'like', "%{$criterio}%");
            });

            $query->where('companies_id', $companies_id);
        };
    }
}
