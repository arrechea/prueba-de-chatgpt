<?php

namespace App\Http\Controllers\Admin\Brand;


use App\Admin;
use App\Http\Controllers\AdminController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Brand\CatalogLocation;
use App\Librerias\Catalog\Tables\GafaFit\CatalogBrand;
use App\Librerias\Dashboards\LibDashboards;
use App\Librerias\Money\LibMoney;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Http\Requests\AdminRequest as Request;
use App\Models\Currency\Currencies;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BrandController extends BrandLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);


        $brand = $this->getBrand();

        $this->middleware(function ($request, $next) use ($brand) {
            $user = auth()->user();
            if (LibPermissions::userCannotAccessTo($user, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });

        $this->middleware(function ($request, $next) use ($brand) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::BRANDS_VIEW, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'index',
        ]);


    }


    public function dashboard(Request $request, Company $company, Brand $brand, Currencies $currencies)
    {
        $monthReservations = LibDashboards::reservationsBrandsMonth($brand);

        $monthPurchases = LibDashboards::purchasesBrandMonth($brand);
        $currency = $brand->currency;
        $price = LibMoney::currencyFormat($currency, $monthPurchases);


        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogLocation::class));
        }

        $catalogo = new CatalogLocation();

        return VistasGafaFit::view('admin.brand.index', [
            'catalogo'          => $catalogo,
            'reservationNumber' => $monthReservations,
            'price'             => $price,
        ]);

    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogBrand::class));
        }

        $catalogo = new CatalogBrand();

        return VistasGafaFit::view('admin.brand.brands.index', [
            'catalogo' => $catalogo,
        ]);

    }
}
