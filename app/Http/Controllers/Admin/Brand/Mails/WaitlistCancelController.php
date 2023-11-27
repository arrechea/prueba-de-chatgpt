<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 18/10/2018
 * Time: 15:41
 */

namespace App\Http\Controllers\Admin\Brand\Mails;

use App\Admin;
use App\Http\Controllers\Admin\Brand\BrandLevelController;
use App\Http\Requests\AdminRequest as Request;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Brand\Mails\CatalogWaitlistCancel;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Mails\MailsWaitlistCancel;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class WaitlistCancelController extends BrandLevelController
{
    public function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $Brands = $this->getBrand();

        $this->middleware(function ($request, $next) use ($Brands) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MAILS_CANCEL_WAITLIST_VIEW, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });

        $this->middleware(function ($request, $next) use ($Brands) {

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MAILS_CANCEL_WAITLIST_EDIT, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'saveNew',
        ]);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request, Company $company, Brand $brand)
    {

        $waitlistCancel = MailsWaitlistCancel::where('brands_id', $brand->id)->first();

        return VistasGafaFit::view('admin.brand.mails.waitlistCancel.index', [
            'waitlistCancel' => $waitlistCancel,
            'urlForm'        => route('admin.company.brand.mails.waitlist-cancel.save.new', [
                'company'        => $this->getCompany(),
                'brand'          => $this->getBrand(),
            ]),
        ]);
    }

    /**
     * @param Request             $request
     * @param Company             $company
     * @param Brand               $brand
     *
     * @param MailsWaitlistCancel $mail
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveNew(Request $request, Company $company, Brand $brand)
    {
        $nuevo = CatalogFacade::save($request, CatalogWaitlistCancel::class);

        return redirect()->back();
    }
}
