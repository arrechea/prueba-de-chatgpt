<?php

namespace App\Librerias\Catalog\Tables\GafaFit;


use App\Admin;
use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibCatalogoRelation;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Permissions\Ability;
use App\Librerias\Permissions\Permission;
use App\Librerias\Permissions\Role;
use App\Librerias\Permissions\Role\RoleRelations;
use App\Librerias\Permissions\Role\RoleTrait;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CatalogRol extends LibCatalogoModel
{
    use RoleRelations, RoleTrait, SoftDeletes;

    protected $table = 'roles';

    /**
     * @return string
     */
    public function GetName()
    {
        return 'Roles';
    }

    /**
     * Devuelve los valores a procesar
     * Esto nos va a servir para declarar todas las valicaciones, etiquetas y opciones de salvado
     *
     * @param Request|null $request
     *
     * @return LibValoresCatalogo[]
     */
    public function Valores(Request $request = null)
    {
        $owner = $this->owner;
        $admins = $this->admins_count;
        $role = $this;

        //Buttons
        $buttons = new LibValoresCatalogo($this, __('roles.Actions'), '', [
            'validator' => '',
        ]);
        $buttons->setGetValueNameFilter(function ($lib, $value) use ($owner, $role) {

            return view('admin.gafafit.roles.buttons', [
                'role' => $role,
            ])->render();
        });

        //Assigned personel
        $assigned_personel = new LibValoresCatalogo($this, __('roles.assigned_personel'), '', [
            'validator' => '',
        ]);
        $assigned_personel->setGetValueNameFilter(function ($lib, $value) use ($role) {
            return $role->admins->count();
        });

        return [
            new LibValoresCatalogo($this, __('roles.Title'), 'title'),
            $assigned_personel,
            /*
             * Company
             */
            (new LibValoresCatalogo($this, __('roles.company'), 'companies_id', [
                'validator'     => 'nullable|exists:companies,id',
                'type'          => 'select',
                'forzeNullable' => true,
                'other'         => new LibCatalogoRelation([
                    Company::class,
                    $role->company,
                ],
                    'id',
                    'name',
                    'name'
                ),
            ]))->setGetValueNameFilter(function ($lib, $value) {
                return empty($value) ? '--' : $value;
            }),
            /*
             * Brand
             */
            (new LibValoresCatalogo($this, __('roles.brand'), 'brands_id', [
                'validator'     => 'nullable|exists:brands,id',
                'type'          => 'select',
                'forzeNullable' => true,
                'other'         => new LibCatalogoRelation([
                    Brand::class,
                    $role->brand,
                ],
                    'id',
                    'name',
                    'name'
                ),
            ]))->setGetValueNameFilter(function ($lib, $value) {
                return empty($value) ? '--' : $value;
            }),
            /*
             * Location
             */
            (new LibValoresCatalogo($this, __('roles.location'), 'locations_id', [
                'validator'     => 'nullable|exists:locations,id',
                'type'          => 'select',
                'forzeNullable' => true,
                'other'         => new LibCatalogoRelation([
                    Location::class,
                    $this->location,
                ],
                    'id',
                    'name',
                    'name'
                ),
            ], function () use ($role) {
                $role->name = str_slug($role->title);
            }))->setGetValueNameFilter(function ($lib, $value) {
                return empty($value) ? '--' : $value;
            }),
            $buttons,
        ];
    }

    /**
     * Save abbilities
     */
    public function runLastSave()
    {
        $request = request();
        $rol = $this->rol;
        /*
         * Seteamos el owner
        */
        $rol->setOwner();
        $rol->save();

        //Eliminamos asociaciones previas
        $rol->permissions()->delete();

        //Asociamos
        $abilities = new Collection(array_keys($request->get('ability', [])));
        if ($abilities->count() > 0) {
            Permission::insert(
                $abilities->map(function ($ability) use ($rol) {
                    return [
                        'ability_id'  => $ability,
                        'entity_id'   => $rol->id,
                        'entity_type' => Role::class,
                    ];
                })->toArray()
            );
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rol()
    {
        return $this->hasOne(Role::class, 'id');
    }

    /**
     * @param Builder $query
     */
    static protected function filtrarQueries(&$query)
    {
        $request = request();
        $query->with([
            'company',
            'brand',
            'location',
            'owner',
            'admins' => function ($q) {
                $q->distinct('entity_id');
            },
        ]);

        $company = LibFilters::getFilterValue('company_filter', $request);

        $brand = LibFilters::getFilterValue('brand_filter', $request);

        $location = LibFilters::getFilterValue('location_filter', $request);

        if ($company) {
            $query->where('companies_id', $company);
        }
        if ($brand) {
            $query->where('brands_id', $brand);
        }
        if ($location) {
            $query->where('locations_id', $location);
        }
    }

    /**
     * Link del modelo
     *
     * @return string
     */
    public
    function link(): string
    {
        return route('admin.roles.edit', [
            'role' => $this,
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.gafafit.roles.filters');
    }
}
