<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 14/03/2018
 * Time: 04:20 PM
 */

namespace App\Http\Controllers\Admin\GafaFit;


use App\Admin;
use App\Http\Controllers\AdminController;
use App\Http\Requests\AdminRequest as Request;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\GafaFit\CatalogRol;
use App\Librerias\Models\Role\LibRole;
use App\Librerias\Permissions\AbilityGroup;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Permissions\Role;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RolesController extends AdminController
{
    public $admin;

    /**
     * RolesController constructor.
     *
     * @param Admin $admin
     */
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::ROLES_VIEW)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });

        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::ROLES_EDIT)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'edit',
            'editSave',
        ]);

        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::ROLES_CREATE)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'create',
            'createSave',
            'copy',
        ]);

        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::ROLES_DELETE)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'delete',
            'deletePost',
        ]);

        $this->admin = $admin;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogRol::class));
        }

        $catalogo = new CatalogRol();

        return VistasGafaFit::view('admin.gafafit.roles.index', [
            'catalogo' => $catalogo,
        ]);
    }


    /**
     * Create Rol
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $groups = AbilityGroup::with('abilities')->orderBy('ability_groups.order')->get();

        return VistasGafaFit::view('admin.gafafit.roles.create.create', [
            'groups'     => $groups,
            'formAction' => route('admin.roles.create.save'),
        ]);
    }

    /**
     * Save new Role
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createSave(Request $request)
    {
        $nuevoRol = CatalogFacade::save($request, CatalogRol::class);

        return redirect()->to($nuevoRol->link());
    }

    /**
     * @param Request $request
     *
     * @param Role    $role
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, Role $role)
    {
        $groups = AbilityGroup::with('abilities')->orderBy('ability_groups.order')->get();
        $role->load([
            'permissions',
            'company',
            'brand',
            'location',
        ]);

        return VistasGafaFit::view('admin.gafafit.roles.edit.edit', [
            'groups'      => $groups,
            'role'        => $role,
            'permissions' => $role->permissions,
            'formAction'  => route('admin.roles.edit.save', [
                'role' => $role,
            ]),
        ]);
    }

    /**
     * Edit Role
     *
     * @param Request    $request
     *
     * @param CatalogRol $role
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editSave(Request $request, CatalogRol $role)
    {
        CatalogFacade::save($request, CatalogRol::class, $role);

        return redirect()->back();
    }

    /**
     *
     * @param CatalogRol $role
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete(CatalogRol $role)
    {
        return VistasGafaFit::view('admin.gafafit.roles.edit.delete', [
            'id' => $role->id,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function deletePost(Request $request)
    {
        CatalogFacade::delete($request, CatalogRol::class);

        return redirect()->back();
    }

    /**
     * Copy Rol
     *
     * @param Request    $request
     * @param CatalogRol $role
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function copy(Request $request, CatalogRol $role)
    {
        $rol = $role->rol;
        $new = LibRole::clone($rol);

        return redirect()->to($new->rol->link());
    }

    /**
     *
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse|null
     */
    public function companies(Request $request)
    {
        $companies = Company::select('id', 'name')->where('name', 'like', '%' . $request->get('search') . '%')->get();

        return response()->json($companies);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function brands(Request $request)
    {
        $companies = Brand::select('id', 'name')->where('name', 'like', '%' . $request->get('search') . '%')->get();

        return response()->json($companies);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function locations(Request $request)
    {
        $companies = Location::select('id', 'name')->where('name', 'like', '%' . $request->get('search') . '%')->get();

        return response()->json($companies);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function brandsLocations(Request $request)
    {
        $companyId = $request->get('companyId', '');

        return response()->json([
            'brands'    => Brand::select('id', 'name')->where('companies_id', $companyId)->get(),
            'locations' => Location::select('id', 'name', 'brands_id')->where('companies_id', $companyId)->get(),
        ]);
    }
}
