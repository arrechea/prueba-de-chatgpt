<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 18/07/2018
 * Time: 01:36 PM
 */

namespace App\Http\Controllers\Admin\Location\Metrics;


use App\Admin;
use App\Http\Controllers\Admin\Location\LocationLevelController;
use App\Http\Controllers\AdminController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Location\Metrics\CatalogSales;
use App\Librerias\Catalog\Tables\Location\Metrics\CatalogSalesByPaymentType;
use App\Librerias\Chart\Graficas;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Http\Requests\AdminRequest as Request;
use App\Models\Purchase\Purchase;
use App\Models\Purchase\PurchaseItems;
use App\Models\Reservation\Reservation;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SalesController extends LocationLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);
        $location = $this->getLocation();

        $this->middleware(function ($request, $next) use ($location) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::METRICS_VIEW, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
    }


    public function index(Request $request, Company $company, Brand $brand, Location $location, PurchaseItems $purchaseItems)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogSales::class));
        }

        $catalogo = new CatalogSales();
        $catalogo_payments = new CatalogSalesByPaymentType();

        $purchaseItems = PurchaseItems::where('locations_id', $location->id)->get();

        $currency = $brand->currency->prefijo;


        return VistasGafaFit::view('admin.location.metrics.sales', [

            'catalogos' => [
                0 => [
                    'catalogo'      => $catalogo,
                    'ajaxDatatable' => route('admin.company.brand.locations.metrics.sales.index', [
                        'company'  => $company,
                        'brand'    => $brand,
                        'location' => $location,
                    ]),
                    'title'         => __('metrics.sales-metrics.top'),
                ],
                1 => [
                    'catalogo'      => $catalogo_payments,
                    'ajaxDatatable' => route('admin.company.brand.locations.metrics.sales.payments.ajax', [
                        'company'  => $company,
                        'brand'    => $brand,
                        'location' => $location,
                    ]),
                    'title'         => __('metrics.sales.payments'),
                ],
            ],
            'items'     => $purchaseItems,
            'chart'     => Graficas::get_grafica(
                route('admin.company.brand.locations.metrics.sales.ajax', [
                    'company'  => $company,
                    'brand'    => $brand,
                    'location' => $location,
                ]),
                array(
                    'id'           => 'salesLocation',
                    'titulo'       => '',
                    'tipo'         => 'LineChart',
                    'extra_charts' => '["LineChart","SteppedAreaChart","AreaChart","ColumnChart","BarChart", "Table"]',
                    'filters'      => VistasGafaFit::view('admin.location.metrics.filters')->render(),
                    'options'      => 'vAxis: {textPosition: \'in\',format:\'' . $currency . '#,###\'},',
                )),
        ]);

    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function purchaseByPaymentTypes(Request $request, Company $company, Brand $brand, Location $location)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogSalesByPaymentType::class));
        }
    }
}
