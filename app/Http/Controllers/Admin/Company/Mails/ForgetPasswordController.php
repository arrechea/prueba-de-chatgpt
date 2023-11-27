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
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Mails\MailsForgetPassword;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Requests\AdminRequest as Request;

class ForgetPasswordController extends CompanyLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);
        $company = $this->getCompany();

        $this->middleware(function ($request, $next) use ($company) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MAILS_RESET_PASSWORD_VIEW, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });

        $this->middleware(function ($request, $next) use ($company) {

//            $forgetID = $request->route('reservationConfirm');
//
//            if ($forgetID !== null) {
//                if ($forgetID instanceof MailsForgetPassword) {
//                    $password = $forgetID;
//                } else {
//                    $password = MailsForgetPassword::where('id', $forgetID)->first();
//                }
//                if ($password->companies_id !== $company->id) {
//                    return abort(404);
//                }
//            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MAILS_RESET_PASSWORD_EDIT, $company)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'save',
        ]);

//        $this->middleware(function ($request, $next) use ($company) {
//
//            $forgetID = $request->route('reservationConfirm');
//
//            if ($forgetID !== null) {
//                if ($forgetID instanceof MailsForgetPassword) {
//                    $password = $forgetID;
//                } else {
//                    $password = MailsForgetPassword::where('id', $forgetID)->first();
//                }
//                if ($password->companies_id !== $company->id) {
//                    return abort(404);
//                }
//            }
//
//
//            $user = auth()->user();
//            if (LibPermissions::userCannot($user, LibListPermissions::MAILS_DELETE, $company)) {
//                throw new NotFoundHttpException();
//            }
//
//            return $next($request);
//        })->only([
//            'delete',
//            'deletePost',
//        ]);


    }

    public function create(Request $request, Company $company)
    {
        $password = $company->mailForgotPassword;

        return VistasGafaFit::view('admin.company.mails.reset.create.index', [
            'forgotPassword' => $password,
            'urlForm'  => route('admin.company.mails.reset-password.save', [
                'company'  => $this->getCompany(),
                'password' => $password,
            ]),
        ]);
    }

//    public function delete(Request $request, Company $company, MailsForgetPassword $password)
//    {
//        return VistasGafaFit::view('admin.brand.mails.delete.deleteForgetPass', [
//            'company'  => $this->getCompany(),
//            'brand'    => $this->getBrand(),
//            'password' => $password,
//        ]);
//
//    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save(Request $request, Company $company)
    {
        $nuevo = CatalogFacade::save($request, CatalogForgetPassword::class);

        return redirect()->back();
    }

//    /**
//     * @param Request $request
//     * @param Company $company
//     * @param Brand   $brand
//     *
//     * @return \Illuminate\Http\RedirectResponse
//     * @throws \Illuminate\Validation\ValidationException
//     */
//    public function deletePost(Request $request, Company $company)
//    {
//        CatalogFacade::delete($request, CatalogForgetPassword::class);
//
//        return redirect()->back();
//    }


}
