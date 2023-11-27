<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 26/06/2018
 * Time: 01:02 PM
 */

namespace App\Http\Controllers\Admin\Company\Mails;


use App\Admin;
use App\Http\Controllers\Admin\Company\CompanyLevelController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Company\Mails\CatalogForgetPassword;
use App\Librerias\Catalog\Tables\Company\Mails\CatalogImportUserMail;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Mails\MailsForgetPassword;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Requests\AdminRequest as Request;

class MailsImportUserController extends CompanyLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);
        $company = $this->getCompany();

        $this->middleware(function ($request, $next) use ($company) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MAILS_IMPORT_USER_VIEW, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });

        $this->middleware(function ($request, $next) use ($company) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MAILS_IMPORT_USER_EDIT, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'save',
        ]);
    }

    public function create(Request $request, Company $company)
    {
        $importUser = $company->mailImportUser;

        return VistasGafaFit::view('admin.company.mails.import.create.index', [
            'importUser' => $importUser,
            'urlForm'  => route('admin.company.mails.import-user.save', [
                'company'  => $this->getCompany(),
                'password' => $importUser,
            ]),
        ]);
    }

    /**
     * @param Request $request
     * @param Company $company
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save(Request $request, Company $company)
    {
//        dd($request->all());
        $nuevo = CatalogFacade::save($request, CatalogImportUserMail::class);

        return redirect()->back();
    }
}
