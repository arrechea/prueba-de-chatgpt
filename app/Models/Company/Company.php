<?php

namespace App\Models\Company;

use App\Admin;
use App\Http\Requests\AdminRequest;
use App\Interfaces\HasChildLevels;
use App\Interfaces\Linkable;
use App\Models\Brand\Brand;
use App\Models\GafaFitModel;
use App\Models\JsonColumns\JsonColumnTrait;
use App\Models\Location\Location;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class Company extends GafaFitModel implements Linkable, HasChildLevels
{
    use SoftDeletes, CompanyRelations, JsonColumnTrait;

    protected $table = 'companies';

    protected $casts = [
        'extra_fields'                => 'array',
        'extra_fields.gympass'        => 'array',
        'extra_fields.gympass.gym_id' => 'integer',
    ];

    /**
     * Link del modelo
     *
     * @return string
     */
    public function link(): string
    {
        return route('admin.company.dashboard', [
            'company' => $this,
        ]);
    }

    public function company()
    {
        return $this;
    }

    /**
     * Función que regresa todos los administradores asociados a esta compañía.
     * Éstos se relacionan usando la tabla assigned_roles.
     *
     * @return Collection
     */
    public function admins(): Collection
    {
        //Obtiene las ubicaciones y las marcas asociadas a la compañía
        $locations_ids = $this->locations()->pluck('id')->toArray();
        $brands_ids = $this->brands()->pluck('id')->toArray();

        /*
         * Utiliza la relación roles con los campos 'assigned_type' y 'assigned_id'
         * para relacionar los roles nivel compañía asociados con la compañía actual,
         * y los roles nivel location y brand que pertenezcan a las ubicaciones y las
         * marcas asociadas a la compañía.
         */

        return Admin::whereHas('roles', function ($q) use ($brands_ids, $locations_ids) {
            $q->whereNull('assigned_type');
            $q->orWhere([
                ['assigned_type', Company::class],
                ['assigned_id', $this->id],
            ]);
            $q->orWhere(function ($q) use ($brands_ids) {
                $q->where('assigned_type', Brand::class);
                $q->whereIn('assigned_id', $brands_ids);
            });
            $q->orWhere(function ($q) use ($locations_ids) {
                $q->where('assigned_type', Location::class);
                $q->whereIn('assigned_id', $locations_ids);
            });
        })->get();
    }
}
