<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 08/10/2018
 * Time: 09:48 AM
 */

namespace App\Http\Controllers\Admin\Brand\Marketing;


use App\Admin;
use App\Http\Requests\AdminRequest as Request;
use App\Http\Controllers\Admin\Brand\BrandLevelController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Brand\CatalogPurchases;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Purchase\Purchase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PurchasesController extends BrandLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $Brands = $this->getBrand();

        $this->middleware(function ($request, $next) use ($Brands) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::CATALOGS_PURCHASES_VIEW, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
    }

    public function index(Request $request, Company $company, Brand $brand)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogPurchases::class));
        }
        $catalogo = new CatalogPurchases();

        return VistasGafaFit::view('admin.brand.catalogs.purchases.index', [
            'catalogo' => $catalogo,
        ]);

    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Purchase $purchase
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function info(Request $request, Company $company, Brand $brand, int $purchase)
    {
        $purchase=Purchase::withTrashed()->where('id',$purchase)->first();
        if(!$purchase){
            abort(404);
        }

        if ((int)$brand->id != (int)$purchase->brands_id) {
            return abort(404);
        }

        $purchase->load([
            'items',
            'currency',
            'giftcard',
            'user_profile',
            'admin_profile',
        ]);
        //Compra normal
        $purchaseDetails = $purchase->items;
        $currency = $purchase->currency;

        $giftcardDetail = $purchase->giftcard ?? null;
        $user = $giftcardDetail && ($giftcardDetail->redempted_by_user_profiles_id) ? $giftcardDetail->user_profile : null;
        $admin = $giftcardDetail && ($giftcardDetail->redempted_by_admin_profiles_id) ? $giftcardDetail->admin_profile : null;
        $redeem_at = isset($giftcardDetail->redempted_at) && $giftcardDetail->redempted_at ? date_format(date_create($giftcardDetail->redempted_at), 'd/m/Y') : null;

        return VistasGafaFit::view('admin.brand.catalogs.purchases.info', [
            'purchase'       => $purchase,
            'currency'       => $currency,
            'details'        => $purchaseDetails,
            'giftCard'       => $purchase->giftcard,
            'detailGiftCard' => $giftcardDetail,
            'user'           => $user,
            'admin'          => $admin,
            'redeem_at'      => $redeem_at,
        ]);
    }


}
