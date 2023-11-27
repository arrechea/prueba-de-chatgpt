<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AdminController;
use App\Interfaces\Linkable;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\GafaFit\CatalogCompany;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Permissions\LibUserCompanyAccess;
use App\Librerias\Vistas\VistasGafaFit;
use App\Role;
use App\Admin;
use App\Permission;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Requests\AdminRequest as Request;

class HomeController extends AdminController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);
        $this->middleware(function ($request, $next) {
            //Logica de redireccion
            $user = auth()->user();
            if (LibPermissions::userCannotAccessTo($user)) {
                //Como no puede acceder a GafaFit Buscamos roles con permisos de access o all
                $roles = $this->getUserRolesThatCanAccess($user);
                //Buscamos si en los roles tiene acceso a company, brand o location pero en esa jerarquia
                if ($roles->count() > 0) {
                    //Comprobamos si hay access a company
                    $rolQueRedirecciona = $roles->first();
                    $modelo = $rolQueRedirecciona->pivot->assigned_type;
                    $modeloId = $rolQueRedirecciona->pivot->assigned_id;
                    $elementoAlQueIremos = $modelo::find($modeloId);
                    if ($elementoAlQueIremos instanceof Linkable) {
                        return redirect()->to($elementoAlQueIremos->link());
                    }
                } else {
                    //Comprobamos roles indirectos de acceso (no asignados directamente sino desde gafafit)
                    $elementoAlQueIremos = LibUserCompanyAccess::UserGeneralAccessFirstCompany($user);
                    if($elementoAlQueIremos){
                        return redirect()->to($elementoAlQueIremos->link());
                    }
                }
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
    }

    /**
     * @param Admin $user
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    private function getUserRolesThatCanAccess(Admin $user)
    {
        $permisoAccess = LibPermissions::ACCESS;
        $permisoAll = LibPermissions::ALL;

        return $user->roles()
            ->whereHas('abilities', function ($query) use ($permisoAccess, $permisoAll) {
                $query->where('abilities.name', '=', $permisoAccess);
                $query->orWhere('abilities.name', '=', $permisoAll);
            })
            ->whereNotNull('assigned_roles.assigned_type')
            ->whereNotNull('assigned_roles.assigned_id')
            ->with([
                'abilities',
            ])
            ->limit(1)
            ->get();
    }

    /**
     * Este es el controllador principal que serán enviados los usuarios cuando no cumplan con determinadas
     * caracteristicas
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogCompany::class));
        }
        $catalogo = new CatalogCompany();

        return VistasGafaFit::view('admin.home', [
            'catalogo' => $catalogo,
        ]);
    }
}
