<?php

namespace App\Http\Controllers\Admin\Company;

use App\Admin;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Company\CatalogUserProfile;
use App\Librerias\Models\Users\LibUsers;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Http\Requests\AdminRequest as Request;
use App\Models\Company\Company;
use App\Models\Countries;
use App\Models\User\UserCategory;
use App\Models\User\UserProfile;
use App\User;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Librerias\Webhooks\LibClientSites;

class UserController extends CompanyLevelController
{
    /**
     * UserController constructor.
     *
     * @param Admin $admin
     */
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $company = $this->getCompany();

        $this->middleware(function ($request, $next) use ($company) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::USER_VIEW, $company)) {
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
                        return $item['name'] === 'comp_id';
                    })->first()['value'] ?? 0;

                if ($companies_id !== \request()->route('company')->id)
                    return abort(404);
            }

            return $next($request);
        })->only([
            'index',
        ]);
        $this->middleware(function ($request, $next) use ($company) {
            $user = \request()->route('user');
            if (!$user || $user->companies_id != \request()->route('company')->id) {
                return abort(404);
            }

            if (\request()->method() === 'POST') {
                if (!\request()->has('companies_id') || \request()->get('companies_id') != \request()->route('company')->id)
                    return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::USER_EDIT, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'edit',
            'saveEdit',
        ]);
        $this->middleware(function ($request, $next) use ($company) {
            $user = \request()->route('user');
            if (!$user || $user->companies_id != \request()->route('company')->id) {
                return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::USER_DELETE, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'delete',
            'deletePost',
        ]);
        $this->middleware(function ($request, $next) use ($company) {
            if (\request()->method() === 'POST') {
                if (!\request()->has('companies_id') || \request()->get('companies_id') != \request()->route('company')->id)
                    return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::USER_CREATE, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'create',
            'saveNew',
        ]);
    }

    /**
     * Lista de usuarios
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request, Company $company)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogUserProfile::class));
        }

        $catalogo = new CatalogUserProfile();

        return VistasGafaFit::view('admin.company.users.index', [
            'catalogo' => $catalogo,
            'company'  => $this->getCompany(),
        ]);
    }

    /**
     * Vista de edición de perfil
     *
     * @param User $user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, Company $company, UserProfile $user)
    {
        $urlForm = route('admin.company.users.save', [
            'company' => $this->getCompany(),
            'user'    => $user->id,
        ]);

        return VistasGafaFit::view('admin.company.users.edit.index', [
            'urlForm'       => $urlForm,
            'user_id'       => $user->users_id,
            'profileToEdit' => $user,
            'company'       => $this->getCompany(),
            'countries'     => Countries::all(),
            'categories'    => UserCategory::where('companies_id', $this->getCompany()->id)->get(),
        ]);
    }

    /**
     * Guardado de los cambios al perfil
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveEdit(Request $request, Company $company, UserProfile $user)
    {

        CatalogFacade::save($request, CatalogUserProfile::class);

        // Synchronize user in remote company's site
        LibClientSites::updateUser($user,$request->all());

        return redirect()->back();
    }

    /**
     * Vista de borrado de perfil
     *
     * @param User $user
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
//    public function delete(Request $request, Company $company, UserProfile $user)
//    {
//        return VistasGafaFit::view('admin.company.users.delete', [
//            'id'      => $user->id,
//            'company' => $this->getCompany(),
//        ]);
//    }
//
//    /**
//     * Borrado de perfil
//     *
//     * @param Request $request
//     *
//     * @return \Illuminate\Http\RedirectResponse
//     * @throws \Illuminate\Validation\ValidationException
//     */
//    public function deletePost(Request $request, Company $company, UserProfile $user)
//    {
//        CatalogFacade::delete($request, CatalogUserProfile::class);
//
//        return redirect()->back();
//    }

    /**
     * Acceso a los países desde nivel compañía en usuarios
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function countries(Request $request, Company $company)
    {
        $search = $request->get('term');
        $countries = Countries::select('id', 'name as text')->where('name', 'like', '%' . $search . '%')->get()->toJson();

        return $countries;
    }

    /**
     * Vista de creación
     *
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request, Company $company)
    {
        return VistasGafaFit::view('admin.company.users.create.index');
    }

    /**
     * Guarda un nuevo perfil
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
        $email = $request->get('email', '');
        $password = $request->get('password', str_random(10));

        $profile = LibUsers::createProfileByEmailAndCompany($request, $email, $company, $password);

        // Synchronize new user in remote company's site
        LibClientSites::addUser($profile,['email' => $email, 'password' => $password]);

        return redirect()->route('admin.company.users.edit', [
            'company' => $this->getCompany(),
            'user'    => $profile->id,
        ]);
    }
}
