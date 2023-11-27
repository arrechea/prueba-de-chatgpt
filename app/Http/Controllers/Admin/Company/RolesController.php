<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 21/08/2018
 * Time: 12:04 PM
 */

namespace App\Http\Controllers\Admin\Company;


use App\Admin;
use App\Http\Requests\AdminRequest as Request;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Company\CatalogRol;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Helpers\LibRoute;
use App\Librerias\Models\Role\LibRole;
use App\Librerias\Permissions\AbilityGroup;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Permissions\Role;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RolesController extends CompanyLevelController
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
        $company = $this->getCompany();

        $this->middleware(function ($request, $next) use ($company) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::ROLES_VIEW, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });

        $this->middleware(function ($request, $next) use ($company) {
            if (\request()->ajax()) {
                $filters = new Collection((array)$request->get('filters', []));
                if (!$filters)
                    return abort(404);

                $companies_id = (int)LibFilters::getFilterValue('company_filter', $request);

                if (!$companies_id || $companies_id !== $this->getCompany()->id)
                    return abort(404);
            }

            return $next($request);
        })->only([
            'index',
        ]);

        $this->middleware(function ($request, $next) use ($company) {
            $allowed_brands = $company->active_brands()->pluck('id')->toArray();
            $allowed_locations = $company->active_locations()->pluck('id')->toArray();

            if (!$request->has('companies_id') || (int)$request->get('companies_id') !== ($this->getCompany())->id)
                return abort(400);

            if ($request->has('brands_id') && $request->get('brands_id') && !in_array($request->get('brands_id'), $allowed_brands))
                return abort(400);

            if ($request->has('locations_id') && $request->get('locations_id') && !in_array($request->get('locations_id'), $allowed_locations))
                return abort(400);

            return $next($request);
        })->only([
            'editSave',
            'createSave',
        ]);

        $this->middleware(function ($request, $next) use ($company) {
            $role = $request->route('role');

            if ($company->id !== $role->companies_id) {
                abort(400);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::ROLES_EDIT, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'edit',
            'editSave',
        ]);

        $this->middleware(function ($request, $next) use ($company) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::ROLES_CREATE, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'copy',
            'create',
            'createSave',
        ]);

        $this->middleware(function ($request, $next) use ($company) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::ROLES_DELETE, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'delete',
            'deletePost',
        ]);

        $this->admin = $admin;
    }

    /**
     * Listado de roles
     *
     * @param Request $request
     * @param Company $company
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request, Company $company)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogRol::class));
        }

        $catalogo = new CatalogRol();

        return VistasGafaFit::view('admin.company.roles.index', [
            'catalogo' => $catalogo,
        ]);
    }


    /**
     * Create Rol
     *
     * @param Request $request
     * @param Company $company
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request, Company $company)
    {
        $groups = AbilityGroup::with('abilities')->orderBy('ability_groups.order')->get();

        $save_route = route('admin.company.roles.create.save', [
            'company' => $company,
        ]);

        return VistasGafaFit::view('admin.company.roles.create.create', [
            'groups'     => $groups,
            'formAction' => $save_route,
        ]);
    }

    /**
     * Save new Role
     *
     * @param Request $request
     *
     * @param Company $company
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function createSave(Request $request, Company $company)
    {
        $nuevoRol = CatalogFacade::save($request, CatalogRol::class);

        return redirect()->route('admin.company.roles.edit', [
            'company' => $company,
            'role'    => $nuevoRol,
        ]);
    }

    /**
     * @param Request $request
     *
     * @param Company $company
     * @param Role    $role
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, Company $company, Role $role)
    {
        $groups = AbilityGroup::with('abilities')->orderBy('ability_groups.order')->get();
        $role->load([
            'permissions',
            'company',
            'brand',
            'location',
        ]);

        return VistasGafaFit::view('admin.company.roles.edit.edit', [
            'groups'      => $groups,
            'role'        => $role,
            'permissions' => $role->permissions,
            'formAction'  => route('admin.company.roles.edit.save', [
                'role'    => $role,
                'company' => $company,
            ]),
        ]);
    }

    /**
     * Edit Role
     *
     * @param Request $request
     *
     * @param Company $company
     * @param Role    $role
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function editSave(Request $request, Company $company, Role $role)
    {
        CatalogFacade::save($request, CatalogRol::class);

        return redirect()->back();
    }

    /**
     *
     * @param Request $request
     * @param Company $company
     * @param Role    $role
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete(Request $request, Company $company, Role $role)
    {
        return VistasGafaFit::view('admin.company.roles.edit.delete', [
            'id' => $role->id,
        ]);
    }

    /**
     * @param Request $request
     *
     * @param Company $company
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function deletePost(Request $request, Company $company)
    {
        CatalogFacade::delete($request, CatalogRol::class);

        return redirect()->back();
    }

    /**
     * Copy Rol
     *
     * @param Request    $request
     * @param Company    $company
     * @param CatalogRol $role
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function copy(Request $request, Company $company, CatalogRol $role)
    {
        $rol = $role->rol;
        $new = LibRole::cloneCompanyLevel($rol, $company);

        return redirect()->route('admin.company.roles.edit', [
            'company' => $company,
            'role'    => $new,
        ]);
    }

    /**
     *
     *
     * @param Request $request
     *
     * @param Company $company
     *
     * @return \Illuminate\Http\JsonResponse|null
     */
    public function companies(Request $request, Company $company)
    {
        $companies = Company::select('id', 'name')->where('name', 'like', '%' . $request->get('search') . '%')->get();

        return response()->json($companies);
    }

    /**
     * @param Request $request
     *
     * @param Company $company
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function brands(Request $request, Company $company)
    {
        $brands = $company->active_brands()
            ->where('name', 'like', "%{$request->get('search')}%")->get();

        return response()->json($brands);
    }

    /**
     * @param Request $request
     *
     * @param Company $company
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function locations(Request $request, Company $company)
    {
        $locations = $company->active_locations()
            ->where('name', 'like', "%{$request->get('search')}%")->get();

        return response()->json($locations);
    }

    /**
     * @param Request $request
     *
     * @param Company $company
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function brandsLocations(Request $request, Company $company)
    {
        $companyId = $request->get('companyId', '');

        return response()->json([
            'brands'    => $company->active_brands()->get(),
            'locations' => $company->active_locations()->get(),
        ]);
    }
}
