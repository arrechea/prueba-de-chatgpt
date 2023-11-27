<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 11/04/2018
 * Time: 10:06 AM
 */

namespace App\Http\Controllers\Admin\Company;


use App\Admin;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Company\CatalogAdmin;
use App\Librerias\Catalog\Tables\Company\CatalogAdminProfile;
use App\Librerias\Models\Admin\LibAdmin;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Http\Requests\AdminRequest as Request;
use App\Models\Admin\AdminProfile;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Countries;
use App\Models\Location\Location;
use function GuzzleHttp\Promise\all;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdministratorController extends CompanyLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $company = $this->getCompany();

        $this->middleware(function ($request, $next) use ($company) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::ADMIN_VIEW, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
        $this->middleware(function ($request, $next) use ($company) {
            if (\request()->ajax()) {
                $filters = new Collection((array)$request->get('filters', []));
                if (!$filters)
                    return abort(404);

                $companies_id = (int)$filters->filter(function ($item) {
                        return $item['name'] === 'id';
                    })->first()['value'] ?? 0;

                if ($companies_id !== \request()->route('company')->id)
                    return abort(404);
            }

            return $next($request);
        })->only([
            'index',
        ]);
        $this->middleware(function ($request, $next) use ($company) {
            $admin = \request()->route('administrator');
            if (!$admin || $admin->companies_id != \request()->route('company')->id) {
                return abort(404);
            }

            if (\request()->method() === 'POST') {
                if (!\request()->has('companies_id') || \request()->get('companies_id') != \request()->route('company')->id)
                    return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::ADMIN_EDIT, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'edit',
            'saveEdit',
        ]);
        $this->middleware(function ($request, $next) use ($company) {
            if (\request()->method() === 'POST') {
                if (!\request()->has('companies_id') || \request()->get('companies_id') != \request()->route('company')->id)
                    return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::ADMIN_CREATE, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'create',
            'saveNew',
        ]);
        $this->middleware(function ($request, $next) use ($company) {
            $admin = \request()->route('administrator');
            if (!$admin || $admin->companies_id != \request()->route('company')->id) {
                return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::ADMIN_DELETE, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'delete',
            'deletePost',
        ]);
        $this->middleware(function ($request, $next) use ($company) {
            $admin = \request()->route('administrator');
            $profile = \request()->route('profile');
            $company_profile = $admin->profile()->where('companies_id', $company->id)->first();
            if (!$profile || $profile->companies_id != \request()->route('company')->id) {
                return abort(404);
            }
            if ($profile->id !== $company_profile->id) {
                abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::ADMIN_ASIGN_ROLES, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'assignmentRoles',
            'assignmentRolesSave',
            'getAdminRoles',
        ]);
        $this->middleware(function ($request, $next) use ($company) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::ADMIN_ASIGN_ROLES, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'getCompanyRoles',
            'getCompanyRolesAndBrands',
        ]);
    }

    /**
     * Regresa el listado de admin_profiles de la compañía si se envía un ajax.
     * Si no se envía un ajax se regresa la vista del listado.
     *
     * @param Request $request
     *
     * @param Company $company
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request, Company $company)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogAdminProfile::class));
        }

        $catalogo = new CatalogAdminProfile();

        return VistasGafaFit::view('admin.company.Administrator.index', [
            'catalogo' => $catalogo,
        ]);
    }

    /**
     * Regresa la vista de edición junto con el perfil a editar
     *
     * @param Request      $request
     * @param Company      $company
     * @param AdminProfile $administrator
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit(Request $request, Company $company, AdminProfile $administrator)
    {
        if ($administrator->deleted_at !== null)
            return abort(404);

        if ($administrator->companies_id !== $this->getCompany()->id)
            return redirect()->back();

        return VistasGafaFit::view('admin.company.Administrator.edit.index', [
            'urlForm'      => route('admin.company.administrator.save', [
                'company'       => $this->getCompany(),
                'administrator' => $administrator,
            ]),
            'adminToEdit'  => $administrator->admin,
            'adminProfile' => $administrator,
        ]);
    }

    /**
     * Regresa la vista de creación. Esta es un input de email con un botón de creación.
     * Basándose en el email, se crea o no el nuevo perfil.
     *
     * @param Request $request
     *
     * @param Company $company
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request, Company $company)
    {
        return VistasGafaFit::view('admin.company.Administrator.create.index', [
            'urlForm' => route('admin.company.administrator.save.new', [
                'company' => $this->getCompany(),
            ]),
        ]);
    }

    /**
     * Guarda un perfil de administrador.
     * Primero comprueba si existe el administrador dentro de la compañía y si éste esta asociado a ella.
     *
     * @param Request      $request
     * @param Company      $company
     * @param AdminProfile $administrator
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveEdit(Request $request, Company $company, AdminProfile $administrator)
    {
        \request()->merge(['current_company' => $this->getCompany()]);

        if ($administrator->companies_id !== $this->getCompany()->id)
            return redirect()->back();

        CatalogFacade::save($request, CatalogAdminProfile::class);

        return redirect()->back();
    }

    /**
     * Crea un nuevo perfil si es que el email pasado no existe.
     * Si no hay un administrador con ese email, también se crea.
     *
     * @param Request $request
     *
     * @param Company $company
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveNew(Request $request, Company $company)
    {
        request()->merge(['current_company' => $this->getCompany()]);

        $nuevo = LibAdmin::createProfileByEmailAndCompany($request, CatalogAdmin::class, CatalogAdminProfile::class);

        return redirect()->route('admin.company.administrator.edit', [
            'company'       => $this->getCompany(),
            'administrator' => $nuevo,
        ]);
    }

    /**
     * Regresa la vista de confirmación de eliminado
     *
     * @param Request      $request
     * @param Company      $company
     * @param AdminProfile $administrator
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
//    public function delete(Request $request, Company $company, AdminProfile $administrator)
//    {
//        if ($administrator->companies_id !== $this->getCompany()->id)
//            return redirect()->back();
//
//        return VistasGafaFit::view('admin.company.Administrator.edit.delete', [
//            'id' => $administrator->id,
//        ]);
//    }
//
//    /**
//     * Borra el perfil de administrador, comprobando primero que éste es parte de la
//     * compañía.
//     * compañía.
//     *
//     * @param Request      $request
//     * @param Company      $company
//     * @param AdminProfile $administrator
//     *
//     * @return \Illuminate\Http\RedirectResponse
//     * @throws \Illuminate\Validation\ValidationException
//     */
//    public function deletePost(Request $request, Company $company, AdminProfile $administrator)
//    {
//        if ($administrator->companies_id !== $this->getCompany()->id)
//            return redirect()->back();
//
//        CatalogFacade::delete($request, 'admin_profiles');
//
//        return redirect()->route('admin.company.administrator.index', [
//            'company' => $this->getCompany(),
//        ]);
//    }

    /**
     * @param Request      $request
     * @param Company      $company
     * @param Admin        $administrator
     *
     * @param AdminProfile $profile
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function assignmentRoles(Request $request, Company $company, Admin $administrator, AdminProfile $profile)
    {

        return VistasGafaFit::view('admin.company.Administrator.edit.assignmentRoles', [
            'adminToEdit'  => $administrator,
            'companies'    => new Collection([$company]),
            'adminProfile' => $profile,
        ]);
    }

    /**
     * @param Request      $request
     * @param Company      $company
     * @param Admin        $administrator
     *
     * @param AdminProfile $profile
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAdminRoles(Request $request, Company $company, Admin $administrator, AdminProfile $profile)
    {
//        $administrator = $administrator->admin;
        $roles = LibAdmin::getRoles($administrator);

        return response()->json($roles);
    }

    /**
     * Obtains Company roles and GafaFit Roles
     *
     * @param Request $request
     * @param Company $company
     *
     * @return \Illuminate\Http\JsonResponse
     * @internal param Company $companyToGet
     *
     */
    public function getCompanyRoles(Request $request, Company $company)
    {
        if ($company->id !== $this->getCompany()->id) {
            abort(404);
        }

        return response()->json($company->getPosibleRoles());
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param         $brandToGet
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBrandRoles(Request $request, Company $company, $brandToGet)
    {
        $brandToGet = Brand::where('companies_id', $company->id)->find($brandToGet);
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
     * @param Company $company
     * @param         $locationToGet
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLocationRoles(Request $request, Company $company, $locationToGet)
    {
        $locationToGet = Location::where('companies_id', $company->id)->find($locationToGet);
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
     * @param Company $company
     *
     * @return \Illuminate\Http\JsonResponse
     * @internal param $companyToGet
     *
     */
    public function getCompanyRolesAndBrands(Request $request, Company $company)
    {
        $company->load([
            'brands',
            'brands.locations',
        ]);

        return response()->json([
            'roles'  => $company->getPosibleRoles(),
            'brands' => $company->brands,
        ]);
    }

    /**
     * @param Request      $request
     * @param Company      $company
     * @param Admin        $administrator
     *
     * @param AdminProfile $profile
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignmentRolesSave(Request $request, Company $company, Admin $administrator, AdminProfile $profile)
    {
        LibAdmin::assignRoles($request, $administrator, $company);

        return response()->json(true);
    }

    /**
     * Regresa los países en la base de datos que concuerden con la búsqueda.
     *
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
}
