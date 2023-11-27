<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 28/06/2018
 * Time: 11:23 AM
 */

namespace App\Http\Controllers\Admin\Brand\Mails;


use App\Admin;
use App\Http\Controllers\Admin\Brand\BrandLevelController;
use App\Http\Requests\AdminRequest as Request;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Brand\Mails\CatalogMailsPurchases;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Mails\MailsPurchase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MailsPurchasesController extends BrandLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);
        $Brands = $this->getBrand();

        $this->middleware(function ($request, $next) use ($Brands) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MAILS_CONFIRM_PURCHASE_VIEW, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });

        $this->middleware(function ($request, $next) use ($Brands) {
//            $purchaseID = $request->route('mailPurchase');
//
//            if ($purchaseID !== null) {
//                if ($purchaseID instanceof MailsPurchase) {
//                    $purchasemail = $purchaseID;
//                } else {
//                    $purchasemail = MailsPurchase::where('id', $purchaseID)->first();
//
//                }
//
//                if ($purchasemail->brands_id !== $Brands->id) {
//                    return abort(404);
//                }
//            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MAILS_CONFIRM_PURCHASE_EDIT, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'saveNew',
        ]);
    }

    /**
     * Funcion Create valida si se tienen datos que editar en la tabla si no da la opcion para guardar nuevos datos
     *
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request, Company $company, Brand $brand)
    {
        $mailPurchase = MailsPurchase::where('brands_id', $brand->id)->first();

        return VistasGafaFit::view('admin.brand.mails.purchases.create.index', [
            'mailPurchase' => $mailPurchase,
            'urlForm'      => route('admin.company.brand.mails.mail-purchase.save.new', [
                'company' => $this->getCompany(),
                'brand'   => $this->getBrand(),

            ]),
        ]);

    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveNew(Request $request, Company $company, Brand $brand)
    {
        $nuevo = CatalogFacade::save($request, CatalogMailsPurchases::class);

        return redirect()->back();
    }
}
