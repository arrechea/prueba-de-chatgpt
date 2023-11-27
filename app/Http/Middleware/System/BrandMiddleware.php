<?php

namespace App\Http\Middleware\System;

use App\Interfaces\Linkable;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Settings\LibSettings;
use App\Models\Brand\Brand;
use App\Models\Location\Location;
use Closure;
use Illuminate\Database\Eloquent\Model;
use View;

class BrandMiddleware
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
        $brand = $this->getBrand($request);

        $user = auth()->user();

        if (!$brand || LibPermissions::userCannotAccessTo($user, $brand)) {
            $location = $this->getLocation($request);

            if (!$location) {
                //Si no hay location debemos ocuparnos de esto
                $brand->load([
                    'locations',
                ]);
                if ($brandToSend = $this->checkAccessChildrens($user, $brand)) {
                    //Si no hay ni location ni brand buscar primer brand y enviar
                    if ($brandToSend instanceof Linkable) {
                        return redirect()->to($brandToSend->link());
                    }
                }

                //Ningun hijo tiene redireccion
                return redirect()->route('admin.home');
            }
        }

        if (isset($brand) && !$brand->isActive()) {
            return abort(403, __('brand.InactiveBrandMessage'));
        }
//        View::share('settings', new LibSettings());
        View::share('brand', $brand);
        View::share('locations', $brand->locations);

        return $next($request);
    }

    /**
     * @param         $user
     * @param Brand   $brand
     *
     * @return null|Model
     */
    public function checkAccessChildrens($user, Brand $brand)
    {
        $permisoAccess = LibPermissions::ACCESS;
        $permisoAll = LibPermissions::ALL;
        $locationsIds = $brand->locations->map(function ($location) {
            return $location->id;
        });

        $rol = $user->roles()
            ->whereHas('abilities', function ($query) use ($permisoAccess, $permisoAll) {
                $query->where('abilities.name', '=', $permisoAccess);
                $query->orWhere('abilities.name', '=', $permisoAll);
            })
            ->where(function ($query) use ($locationsIds) {
                $query->where('assigned_type', Location::class)
                    ->whereIn('assigned_id', $locationsIds);
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
