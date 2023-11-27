<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 12/03/2019
 * Time: 15:57
 */

namespace App\Http\Controllers\Admin\Brand;


use App\Admin;
use App\Http\Requests\AdminRequest as Request;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Brand\CatalogSubscriptions;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Combos\Combos;
use App\Models\Company\Company;
use App\Models\Subscriptions\Subscription;
use App\Models\User\UserProfile;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Librerias\GafaPay\LibGafaPay;


class SubscriptionsController extends BrandLevelController
{
    public function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $brand = $this->getBrand();

        $this->middleware(function ($request, $next) use ($brand) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::CATALOGS_SUBSCRIPTIONS_VIEW, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
    }

    public function index(Request $request, Company $company, Brand $brand)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogSubscriptions::class));
        }

        $catalogo = new CatalogSubscriptions();

        return VistasGafaFit::view('admin.brand.catalogs.subscriptions.index', [
            'catalogo' => $catalogo,
        ]);
    }

    public function details(Request $request, Company $company, Brand $brand, Subscription $subscription)
    {
        $payments = $subscription->payments()->orderBy('created_at','desc')->get();

        return VistasGafaFit::view('admin.brand.catalogs.subscriptions.details', [
            'subscription' => $subscription,
            'payments'     => $payments,
        ]);
    }

    public function delete(Request $request, Company $company, Brand $brand, Subscription $subscription)
    {

        return VistasGafaFit::view('admin.brand.catalogs.subscriptions.delete', [
            'subscription' => $subscription,

        ]);
    }

    public function subscriptionCancel(Request $request,Company $company, Brand $brand, Subscription $subscription)
    {

        $cancelGafapay = LibGafaPay::cancelSubscription($subscription->subscription_id_gafapay);
        if($cancelGafapay)
            $subscription->cancel();

        return redirect()->back();

    }

    public function users(Request $request, Company $company, Brand $brand)
    {
        $search = $request->get('term');

        return UserProfile::select('id', DB::raw('concat(first_name," ",last_name," (",email,")") as text'))
            ->where([
                ['status', 'active'],
                ['companies_id', $company->id],
            ])
            ->where(function ($q) use ($search) {
                $q->where('email', 'like', '%' . $search . '%');
                $q->orWhere('first_name', 'like', '%' . $search . '%');
                $q->orWhere('last_name', 'like', '%' . $search . '%');
            })->get()->toJson();
    }
}
