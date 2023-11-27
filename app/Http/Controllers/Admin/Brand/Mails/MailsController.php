<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 22/06/2018
 * Time: 01:47 PM
 */

namespace App\Http\Controllers\Admin\Brand\Mails;


use App\Admin;
use App\Http\Controllers\Admin\Brand\BrandLevelController;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Requests\AdminRequest as Request;

class MailsController extends BrandLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);
        $brand = $this->getBrand();

        $this->middleware(function ($request, $next) use ($brand) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MAILS_VIEW, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });


        $this->middleware(function ($request, $next) use ($brand) {


            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MAILS_EDIT, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'welcome',
            'reservationCancel',
            'reservationConfirm',
            'forgetPassword',
            'purchases',
        ]);

    }

    public function index(Request $request, Company $company, Brand $brand)
    {
       // return VistasGafaFit::view('admin.brand.mails.welcome');
    }

    public function reservationCancel()
    {
        return VistasGafaFit::view('admin.brand.mails.reservationCancel');
    }

    public function reservationConfirm()
    {
        return VistasGafaFit::view('admin.brand.mails.reservationConfirm');

    }

    public function forgetPassword()
    {
        return VistasGafaFit::view('admin.brand.mails.forgetPassword');
    }

    public function purchases()
    {
        return VistasGafaFit::view('admin.brand.mails.Purchases');
    }

}
