<?php

namespace App\Librerias\Permissions;


use App\Admin;
use App\AssignedRol;
use App\Interfaces\Linkable;
use App\Models\Company\Company;
use App\Models\Brand\Brand;
use Illuminate\Support\Collection;
use App\Models\Location\Location;

abstract class LibUserCompanyAccess
{
    /**
     * @param Admin $user
     *
     * @return null
     */
    static public function UserGeneralAccessFirstCompany(Admin $user)
    {
        $permisoAccess = LibPermissions::ACCESS;
        $permisoAll = LibPermissions::ALL;

        $assignedRol = AssignedRol::where('entity_type', Admin::class)
            ->where('entity_id', $user->id)
            ->whereNull('assigned_type')
            ->whereHas('roles.abilities', function ($query) use ($permisoAccess, $permisoAll) {
                $query->where('abilities.name', '=', $permisoAccess);
                $query->orWhere('abilities.name', '=', $permisoAll);
            })
            ->with([
                'roles.abilities' => function ($query) use ($permisoAccess, $permisoAll) {
                    $query->where('abilities.name', '=', $permisoAccess);
                    $query->orWhere('abilities.name', '=', $permisoAll);
                },
            ])
            ->first();

        if (
            $assignedRol
            &&
            $assignedRol->roles->count() > 0
            &&
            $assignedRol->roles->abilities->count() > 0
        ) {
            $primerAbility = $assignedRol->roles->abilities->first();
            $modelo = $primerAbility->entity_type;
            $modeloId = $primerAbility->entity_id;

            if ($modeloId) {
                //Si es especifico de un modelo
                $elementoAlQueIremos = $modelo::find($modeloId);
                if ($elementoAlQueIremos instanceof Linkable) {
                    return $elementoAlQueIremos;
                }
            } else {
                //Si es para todos los modelos
                return $modelo::where('status', 'active')->first();
            }
        }

        return null;
    }

    /**
     * @param Admin $user
     *
     * @return mixed
     */
    static public function UserCompanyAccess(Admin $user)
    {
        $companiesID = new Collection();

        //--consultar assigned
        $assignedRoles = AssignedRol::where('entity_type', Admin::class)
            ->where('entity_id', $user->id)
            ->where(function ($query) {
                $query->where('assigned_type', Company::class);
                $query->orWhereNull('assigned_type');
            })
            ->whereHas('roles', function ($query) {
                $query->whereHas('abilities', function ($query) {
                    $query->where('abilities.id', 2);//--2 es habilidad de compañia
                });
            })->get();


        //--recorrer assigned
        $hasGafaFitPermission = false;
        $assignedRoles->each(function ($assignedRole) use (&$hasGafaFitPermission, &$companiesID) {
            if ($assignedRole->assigned_type === null) {
                $hasGafaFitPermission = true;
            }
            if (!$hasGafaFitPermission) {
                $companiesID->push($assignedRole->assigned_id);
            }
        });
        //Fin

        if ($hasGafaFitPermission) {
            return Company::select(['id', 'name', 'slug'])->where('status', 'active')->get();
        }


        return Company::select(['id', 'name', 'slug'])->whereIn('id', $companiesID->toArray())->where('status', 'active')->get();
    }

    /**
     * @param Admin $user
     *
     * @return mixed
     */
    static public function UserBrandAccess(Admin $user)
    {
        $brandsID = new Collection();

        //consultar assigned de brand o company
        $assignedRoles = AssignedRol::where('entity_type', Admin::class)
            ->where('entity_id', $user->id)
            ->where(function ($query) {
                $query->where('assigned_type', Brand::class);
                $query->orWhere('assigned_type', Company::class);
                $query->orWhereNull('assigned_type');
            })
            ->whereHas('roles', function ($query) {
                $query->whereHas('abilities', function ($query) {
                    $query->where('abilities.id', 3);//2 es habilidad de marca
                });
            })->get();

        //recorrer assigned de brand y Company
        $hasGafaFitPermission = false;
        $assignedRoles->each(function ($assignedRole) use (&$hasGafaFitPermission, &$brandsID) {
            if ($assignedRole->assigned_type === null) {
                $hasGafaFitPermission = true;
            }
            if (!$hasGafaFitPermission) {
                if ($assignedRole->assigned_type === Brand::class) {
                    //Brand
                    $brandsID->push($assignedRole->assigned_id);
                } else if ($assignedRole->assigned_type === Company::class) {
                    $companyBrands = Brand::where('status', 'active')
                        ->select('id')
                        ->whereHas('company', function ($query) use ($assignedRole) {
                            $query->where('id', $assignedRole->assigned_id);
                            $query->where('status', 'active');
                        })
                        ->get()
                        ->map(function ($brand) {
                            return $brand->id;
                        })->toArray();

                    $brandsID = $brandsID->merge($companyBrands);
                }
            }
        });

        if ($hasGafaFitPermission) {
            //tiene permisos para todas
            return Brand::select(['id', 'name', 'slug', 'companies_id'])->with('company')->where('status', 'active')->get();
        }

        //Fin select(['id', 'name','slug'])
        return Brand::select(['id', 'name', 'slug', 'companies_id'])->with('company')->whereIn('id', $brandsID->toArray())->where('status', 'active')->get();
    }

    /**
     * @param Admin $user
     *
     * @return mixed
     */
    static public function UserLocationAccess(Admin $user)
    {
        $locationsID = new Collection();

        //consultar assigned
        $assignedRoles = AssignedRol::where('entity_type', Admin::class)
            ->where('entity_id', $user->id)
            ->whereHas('roles', function ($query) {
                $query->whereHas('abilities', function ($query) {
                    $query->where('abilities.id', 4);//2 es habilidad de marca
                });
            })->get();

        //recorrer assigned
        $hasGafaFitPermission = false;
        $assignedRoles->each(function ($assignedRole) use (&$hasGafaFitPermission, &$locationsID) {
            if ($assignedRole->assigned_type === null) {
                $hasGafaFitPermission = true;
            }
            if (!$hasGafaFitPermission) {
                if ($assignedRole->assigned_type === Location::class) {
                    //Brand
                    $locationsID->push($assignedRole->assigned_id);
                } else if ($assignedRole->assigned_type === Brand::class) {
                    $BrandsLocation = Location::where('status', 'active')
                        ->select('id')
                        ->whereHas('brand', function ($query) use ($assignedRole) {
                            $query->where('id', $assignedRole->assigned_id);
                            $query->where('status', 'active');
                        })
                        ->get()
                        ->map(function ($location) {
                            return $location->id;
                        })->toArray();

                    $locationsID = $locationsID->merge($BrandsLocation);
                } else if ($assignedRole->assigned_type === Company::class) {
                    $companyLocations = Location::where('status', 'active')
                        ->select('id')
                        ->whereHas('company', function ($query) use ($assignedRole) {
                            $query->where('id', $assignedRole->assigned_id);
                            $query->where('status', 'active');
                        })
                        ->whereHas('brand', function ($query) {
                            $query->where('status', 'active');
                        })
                        ->get()
                        ->map(function ($location) {
                            return $location->id;
                        })->toArray();

                    $locationsID = $locationsID->merge($companyLocations);
                }
            }
        });

        if ($hasGafaFitPermission) {
            //tiene permisos para todas
            return Location::select(['id', 'name', 'slug', 'brands_id', 'companies_id'])->with('brand', 'company')->where('status', 'active')->get();
        }

        //Fin select(['id', 'name','slug'])
        return Location::select(['id', 'name', 'slug', 'brands_id', 'companies_id'])->with('brand', 'company')->whereIn('id', $locationsID->toArray())->where('status', 'active')->get();
    }
}
