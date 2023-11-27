<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 22/03/2019
 * Time: 12:06
 */

namespace App\Http\Controllers\Admin\Brand\Mails;


use App\Admin;
use App\Http\Controllers\Admin\Brand\BrandLevelController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Brand\Mails\CatalogMailsSubscriptionError;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Subscriptions\MailSubscriptionPaymentFailed;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Requests\AdminRequest as Request;

class SubscriptionErrorController extends BrandLevelController
{
    public function __construct(Admin $admin)
    {
        parent::__construct($admin);
        $brand = $this->getBrand();

        $this->middleware(function ($request, $next) use ($brand) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MAILS_ERROR_SUBSCRIPTION_VIEW, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });

        $this->middleware(function ($request, $next) use ($brand) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MAILS_ERROR_SUBSCRIPTION_EDIT, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'edit',
            'save',
        ]);
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, Company $company, Brand $brand)
    {
        $subscriptionError = MailSubscriptionPaymentFailed::where('brands_id', $brand->id)->first();

        return VistasGafaFit::view('admin.brand.mails.subscriptionError.create.index', [
            'subscriptionError' => $subscriptionError,
            'urlForm'           => route('admin.company.brand.mails.subscription-error.save', [
                'company'           => $this->getCompany(),
                'brand'             => $this->getBrand(),
                'subscriptionError' => $subscriptionError,
            ]),
        ]);
    }

    /**
     * @param Request                       $request
     * @param Company                       $company
     * @param Brand                         $brand
     * @param MailSubscriptionPaymentFailed $subscriptionError
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save(Request $request, Company $company, Brand $brand, MailSubscriptionPaymentFailed $subscriptionError)
    {
        $nuevo = CatalogFacade::save($request, CatalogMailsSubscriptionError::class);

        return redirect()->back();
    }
}
