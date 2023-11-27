<?php

namespace App\Http\Controllers\Admin\Company;

use App\Admin;
use App\Http\Requests\AdminRequest as Request;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Company\Company;
use App\Models\Language\Language;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImportUsersController extends CompanyLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);
        $company = $this->getCompany();

        $this->middleware(function ($request, $next) use ($company) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::USER_IMPORT_VIEW, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
        $this->middleware(function ($request, $next) use ($company) {
            if (\request()->route('company')->id != \request()->get('id', 0))
                return abort(404);

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::USER_IMPORT_EDIT, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'import',
        ]);
    }


    public function index(Request $request, Company $company)
    {
        $company = $this->getCompany();
        $languages = Language::all();
        $urlForm = route('admin.company.import.users.import', ['company' => $company]);

        return VistasGafaFit::view('admin.company.user-import.index', [
            'compToEdit' => $company,
            'languages'  => $languages,
            'urlForm'    => $urlForm,
        ]);
    }

    public function import(Request $request, Company $company)
    {
        $company_id = $company->id;
        if ($request->has('csv')) {
//            $request->file('csv')->store("csvs/$company_id");
        }

        return redirect()->back();
    }
}
