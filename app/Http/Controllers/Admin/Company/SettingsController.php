<?php

namespace App\Http\Controllers\Admin\Company;

use App\Admin;
use App\Http\Controllers\AdminController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Company\CatalogCompany;
use App\Librerias\Client\LibClient;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Http\Requests\AdminRequest as Request;
use App\Models\Company\Company;
use App\Models\Countries;
use App\Models\Language\Language;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SettingsController extends CompanyLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);
        $company = $this->getCompany();

        $this->middleware(function ($request, $next) use ($company) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::COMPANY_VIEW, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
        $this->middleware(function ($request, $next) use ($company) {
            if (\request()->route('company')->id != \request()->get('id', 0))
                return abort(404);

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::COMPANY_EDIT, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'saveCompany',
        ]);
        $this->middleware(function ($request, $next) use ($company) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::COMPANY_DELETE, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'delete',
            'deletePost',
        ]);
    }

    public function index(Request $request, Company $company)
    {
        $company = $this->getCompany();
        $urlForm = route('admin.company.settings.save.company', ['company' => $company]);
        $languages = Language::all();

        return VistasGafaFit::view('admin.company.settings.edit.index', [
            'compToEdit' => $company,
            'urlForm'    => $urlForm,
            'languages'  => $languages,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveCompany(Request $request, Company $company)
    {
        $request->merge([
            'status' => 'on',
        ]);
        CatalogFacade::save($request, CatalogCompany::class);

        return redirect()->back();
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete(Request $request, Company $company)
    {
        return VistasGafaFit::view('admin.company.settings.delete', [
            'companyToEdit' => $company,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function deletePost(Request $request, Company $company)
    {
        $companies = auth()->user()->companies;
        $id = (int)$request->get('id');

        if ($id === $company->id) {
            CatalogFacade::delete($request, CatalogCompany::class);

            $first_comp = $companies->where('id', '<>', $id)->first();
            if ($first_comp !== null)
                return redirect()->route('admin.company.dashboard', ['company' => $first_comp]);
            else {
                Auth::logout();

                return redirect()->route('admin.init');
            }
        } else {
            CatalogFacade::delete($request, CatalogCompany::class);

            return redirect()->back();
        }
    }

    /**
     * Genera una clave secreta para el cliente asociado a la compañía
     *
     * @param Request $request
     * @param Company $company
     *
     * @return array
     */
    public function generateSecret(Request $request, Company $company)
    {
        $response=LibClient::generateClientSecret($company);

        return $response ?? abort(403,__('settings.MessageFailedGenerating'));
    }
}
