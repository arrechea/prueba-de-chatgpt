<?php

namespace App\Http\Middleware\System;

use App\Interfaces\Linkable;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Settings\LibSettings;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Request;
use View;

class CompanyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $company = $this->getCompany($request);
        if (!$company->isActive()) {
            return redirect()->route('admin.home');
        }

        $user = auth()->user();

        if (LibPermissions::userCannotAccessTo($user, $company)) {
            $brand = $this->getBrand($request);
            $location = $this->getLocation($request);

            if (!$brand && !$location) {
                $company->load([
                    'brands',
                    'locations',
                ]);
                //Si no hay ni brand ni location a buscar intentaremos redireccionar
                if ($brandToSend = $this->checkAccessChildrens($user, $company)) {
                    //Si no hay ni location ni brand buscar primer brand y enviar
                    if ($brandToSend instanceof Linkable) {
                        return redirect()->to($brandToSend->link());
                    }
                }

                //Ningun hijo tiene redireccion
                return redirect()->route('admin.home');
            }
        }
        $brand = $company->active_brands()->first();
        View::share('settings', new LibSettings());
        View::share('company', $company);
        View::share('brand', $brand);
        if(isset($brand)){
            View::share('locations', $brand->locations);
        }

        return $next($request);
    }

    /**
     * @param         $user
     * @param Company $company
     *
     * @return null|Model
     */
    public function checkAccessChildrens($user, Company $company)
    {
        $permisoAccess = LibPermissions::ACCESS;
        $permisoAll = LibPermissions::ALL;
        $brandsIds = $company->brands->map(function ($brand) {
            return $brand->id;
        });
        $locationsIds = $company->locations->map(function ($location) {
            return $location->id;
        });

        $rol = $user->roles()
            ->whereHas('abilities', function ($query) use ($permisoAccess, $permisoAll) {
                $query->where('abilities.name', '=', $permisoAccess);
                $query->orWhere('abilities.name', '=', $permisoAll);
            })
            ->where(function ($query) use ($brandsIds, $locationsIds) {
                $query
                    ->where(function ($query) use ($brandsIds) {
                        $query->where('assigned_type', Brand::class)
                            ->whereIn('assigned_id', $brandsIds);
                    })
                    ->orWhere(function ($query) use ($locationsIds) {
                        $query->where('assigned_type', Location::class)
                            ->whereIn('assigned_id', $locationsIds);
                    });
            })
            ->limit(1)
            ->get()
            ->first();

        if ($rol) {
            $modelo = $rol->pivot->assigned_type;
            $modeloId = $rol->pivot->assigned_id;

            return $modelo::find($modeloId);
        }

        return null;
    }

    /**
     * @param $request
     *
     * @return Company
     */
    private function getCompany($request)
    {
        $companySlug = $request->route('company');

        if ($companySlug instanceof Company) {
            $company = $companySlug;
        } else {
            $company = Company::where('slug', $companySlug)->first();
        }

        return $company;
    }

    /**
     * @param $request
     *
     * @return Brand
     */
    private function getBrand($request)
    {
        $brandSlug = $request->route('brand');

        if ($brandSlug instanceof Brand) {
            $brand = $brandSlug;
        } else {
            $brand = Brand::where('slug', $brandSlug)->first();
        }

        return $brand;
    }

    /**
     * @param $request
     *
     * @return Location
     */
    private function getLocation($request)
    {
        $locationSlug = $request->route('location');

        if ($locationSlug instanceof Location) {
            $location = $locationSlug;
        } else {
            $location = Location::where('slug', $locationSlug)->first();
        }

        return $location;
    }
}
