<?php

namespace App\Http\Controllers\Admin\GafaFit;

use App\Admin;
use App\Http\Controllers\AdminController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\GafaFit\CatalogCompany;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Http\Requests\AdminRequest as Request;
use App\Models\Company\Company;
use App\Models\Language\Language;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CompanyController extends AdminController
{

    /**
     * CompanyController constructor.
     *
     * @param Admin $admin
     */
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::COMPANY_VIEW)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });

        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::COMPANY_EDIT)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'edit',
            'saveEdit',
        ]);

        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::COMPANY_CREATE)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'create',
            'saveNew',
        ]);

        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::COMPANY_DELETE)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'delete',
            'deletePost',
        ]);

        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (LibPermissions::userCan($user, LibListPermissions::NOTIFICATION_EMAIL_EDIT)) {
                $request->merge(['has_mail_permission' => true]);
            } else {
                $request->merge(['has_mail_permission' => false]);
            }

            return $next($request);
        })->only([
            'saveNew',
            'saveEdit',
        ]);

        $this->admin = $admin;
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogCompany::class));
        }

        $catalogo = new CatalogCompany();

        return VistasGafaFit::view('admin.gafafit.companies.index', [
            'catalogo' => $catalogo,
        ]);
    }

    /**
     * @param int $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(int $id)
    {
        $compToEdit = Company::find($id);

        if ($compToEdit === null)
            return abort(404);

        return VistasGafaFit::view('admin.gafafit.companies.edit.index', [
            'compToEdit' => $compToEdit,
            'urlForm'    => route('admin.companyEdit.save', ['companyToEdit' => $compToEdit]),
            'languages'  => Language::all(),
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return VistasGafaFit::view('admin.gafafit.companies.create.index', [
            'urlForm'   => route('admin.companyEdit.save.new'),
            'languages' => Language::all(),
        ]);
    }

    /**
     * @param int $companyToEdit
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete($companyToEdit)
    {
        return VistasGafaFit::view('admin.gafafit.companies.edit.delete', [
            'id' => $companyToEdit,
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
        CatalogFacade::delete($request, CatalogCompany::class);

        return redirect()->route('admin.companyEdit.index');
    }

    /**
     * @return Admin
     */
    public function getAdmin(): Admin
    {
        return $this->admin;
    }

    /**
     * Función para obtener una lista de administradores dependiendo del término de búsqueda
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function admins(Request $request)
    {
        $search = $request->get('term');

        return Admin::select('id', 'email as text')->where(function ($q) use ($search) {
            $q->where('email', 'like', '%' . $search . '%');
            $q->where('status', 'active');
        })->orWhere(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%');
            $q->where('status', 'active');
        })->get()->toJson();
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveNew(Request $request)
    {
        $nuevo = CatalogFacade::save($request, CatalogCompany::class);

        return redirect()->route('admin.companyEdit.edit', [
            'companyToEdit' => $nuevo->id,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveEdit(Request $request)
    {
        CatalogFacade::save($request, 'companies');

        return redirect()->back();
    }
}
