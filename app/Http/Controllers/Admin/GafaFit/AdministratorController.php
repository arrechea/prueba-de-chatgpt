<?php

namespace App\Http\Controllers\Admin\GafaFit;

use App\Admin;
use App\Http\Controllers\AdminController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\GafaFit\CatalogAdminProfile;
use App\Librerias\Catalog\Tables\GafaFit\CatalogAdmin;
use App\Librerias\Models\Admin\LibAdmin;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Permissions\Role;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Admin\AdminProfile;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Countries;
use App\Models\Location\Location;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Requests\AdminRequest as Request;


class AdministratorController extends AdminController
{

    /**
     * Llamando a los permisos para entrar a administrador
     *
     * AdministratorController constructor.
     *
     * @param Admin $admin
     */
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::ADMIN_VIEW)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::ADMIN_EDIT)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'edit',
            'saveEdit',
            'assignmentRoles',
            'assignmentRolesSave',
            'getAdminRoles',
            'getCompanyRoles',
            'getCompanyRolesAndBrands',
        ]);
        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::ADMIN_CREATE)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'create',
            'saveNew',
        ]);

        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::ADMIN_DELETE)) {
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
     * Mostrando vista de lista de administradores
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogAdmin::class));
        }

        $catalogo = new CatalogAdmin();

        return VistasGafaFit::view('admin.gafafit.administrators.index', [
            'catalogo' => $catalogo,
        ]);
    }

    /**
     * Vista de edicion de usuarios tipo administradores llena el formulario con los datos del usuario a editar
     *
     * @param Request $request
     * @param         $administrator
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, Admin $administrator)
    {
        $countries = Countries::all();

        $profiles = $administrator->profile->map(function ($item) {
            $company = $item->company;
            if ($company) {
                return [
                    'name'    => $item->first_name . ' ' . $item->last_name,
                    'email'   => $item->email,
                    'url'     => LibPermissions::userCan(auth()->user(), LibListPermissions::ADMIN_EDIT, $company) ?
                        route('admin.company.administrator.edit', [
                            'company'       => $company,
                            'administrator' => $item,
                        ]) : null,
                    'company' => $company->name??'--',
                    'status'  => $item->status,
                ];
            }
        });

        return VistasGafaFit::view('admin.gafafit.administrators.edit.index', [
            'admin'        => $administrator,
            'adminProfile' => $administrator,
            'urlForm'      => route('admin.administrator.save', [
                'administrator' => $administrator,
            ]),
            'countries'    => $countries,
            'profiles'     => $profiles,
        ]);
    }

    /**
     * Vista para la creación de un nuevo administrador, salvado de informacion de usuario en creacion
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */
    public function create(Request $request)
    {
//        return VistasGafaFit::view('admin.gafafit.administrators.create.modal');

        return VistasGafaFit::view('admin.gafafit.administrators.create.index', [
            'urlForm' => route('admin.administrator.save.new'),
        ]);
    }

    /**
     * @param Request $request
     * @param Admin   $administrator
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveEdit(Request $request, Admin $administrator)
    {
        CatalogFacade::save($request, CatalogAdmin::class);

        return redirect()->back();
//        LibAdmin::edit($request, $administrator);
//
//        return redirect()->back();
    }

    /**
     * Create user
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveNew(Request $request)
    {
//        $profile = LibAdmin::createProfileByEmailAndCompany($request);
//
//        return redirect()->route('admin.administrator.edit', [
//            'administrator' => $profile,
//        ]);
        $nuevo = CatalogFacade::save($request, CatalogAdmin::class);

        return redirect()->to($nuevo->link());
    }

    /**
     * Vista de asignación de roles para cuando se edita un administrador, este es una vista parcial y se muestra en un
     * modal
     *
     * @param Request $request
     * @param Admin   $administrator
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function assignmentRoles(Request $request, Admin $administrator)
    {
        return VistasGafaFit::view('admin.gafafit.administrators.edit.assignmentRoles', [
            'adminToEdit'  => $administrator,
            'companies'    => Company::all(),
            'rolesGafaFit' => Role::whereNull('owner_type')->get(),
        ]);
    }

    /**
     * @param Request $request
     * @param Admin   $administrator
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAdminRoles(Request $request, Admin $administrator)
    {
        $roles = LibAdmin::getRoles($administrator);

        return response()->json($roles);
    }

    /**
     * Obtains Company roles and GafaFit Roles
     *
     * @param Request $request
     * @param Company $companyToGet
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompanyRoles(Request $request, $companyToGet)
    {
        $companyToGet = Company::find($companyToGet);
        if (!$companyToGet) {
            throw new NotFoundHttpException();
        }

        return response()->json($companyToGet->getPosibleRoles());
    }

    /**
     * @param Request $request
     * @param         $brandToGet
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBrandRoles(Request $request, $brandToGet)
    {
        $brandToGet = Brand::find($brandToGet);
        if (!$brandToGet) {
            throw new NotFoundHttpException();
        }
        $brandToGet->load([
            'roles',
            'company',
            'company.roles',
        ]);

        return response()->json($brandToGet->getPosibleRoles());
    }

    /**
     * @param Request $request
     * @param         $locationToGet
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLocationRoles(Request $request, $locationToGet)
    {
        $locationToGet = Location::find($locationToGet);
        if (!$locationToGet) {
            throw new NotFoundHttpException();
        }
        $locationToGet->load([
            'brand.roles',
            'company.roles',
        ]);

        return response()->json($locationToGet->getPosibleRoles());
    }

    /**
     * @param Request $request
     * @param         $companyToGet
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCompanyRolesAndBrands(Request $request, $companyToGet)
    {
        $companyToGet = Company::find($companyToGet);
        if (!$companyToGet) {
            throw new NotFoundHttpException();
        }
        $companyToGet->load([
            'brands',
            'brands.locations',
        ]);

        return response()->json([
            'roles'  => $companyToGet->getPosibleRoles(),
            'brands' => $companyToGet->brands,
        ]);
    }

    /**
     * @param Request $request
     * @param Admin   $administrator
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignmentRolesSave(Request $request, Admin $administrator)
    {
        LibAdmin::assignRoles($request, $administrator);

        return response()->json(true);
    }

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function countries(Request $request)
    {
        $search = $request->get('term');
        $countries = Countries::select('id', 'name as text')->where('name', 'like', '%' . $search . '%')->get()->toJson();

        return $countries;
    }

    /**
     * @param int $adminToEdit hacer el guardado para eliminacion de usuario
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
//    public function delete($adminToEdit)
//    {
//        return VistasGafaFit::view('admin.gafafit.administrators.edit.delete', [
//            'id' => $adminToEdit,
//        ]);
//    }
//
//    /**
//     * @param Request $request
//     *
//     * @return \Illuminate\Http\RedirectResponse
//     * @throws \Illuminate\Validation\ValidationException
//     */
//    public function deletePost(Request $request)
//    {
//        CatalogFacade::delete($request, 'admins');
//
//        return redirect()->route('admin.administrator.index');
//    }
}


