<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 18/07/2018
 * Time: 01:36 PM
 */

namespace App\Http\Controllers\Admin\Brand\Metrics;


use App\Admin;
use App\Http\Controllers\Admin\Brand\BrandLevelController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Brand\Metrics\CatalogSales;
use App\Librerias\Catalog\Tables\Brand\Metrics\CatalogSalesByPaymentType;
use App\Librerias\Chart\Graficas;
use App\Librerias\Chart\Locations\Sales;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Http\Requests\AdminRequest as Request;
use App\Models\Purchase\PurchaseItems;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SalesController extends BrandLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);
        $brand = $this->getBrand();

        $this->middleware(function ($request, $next) use ($brand) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::METRICS_VIEW, $brand)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(Request $request, Company $company, Brand $brand)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogSales::class));
        }

        $catalogo = new CatalogSales();
        $catalogo_payments = new CatalogSalesByPaymentType();

        $purchaseItems = PurchaseItems::where('brands_id', $brand->id)->get();

        $currency = $brand->currency->prefijo;


        return VistasGafaFit::view('admin.brand.metrics.sales.sales', [

            'catalogos' => [
                0 => [
                    'catalogo'      => $catalogo,
                    'ajaxDatatable' => route('admin.company.brand.metrics.sales.index', [
                        'company' => $company,
                        'brand'   => $brand,
                    ]),
                    'title'         => __('metrics.sales-metrics.top'),
                ],
                1 => [
                    'catalogo'      => $catalogo_payments,
                    'ajaxDatatable' => route('admin.company.brand.metrics.sales.payments.ajax', [
                        'company' => $company,
                        'brand'   => $brand,
                    ]),
                    'title'         => __('metrics.sales.payments'),
                ],
            ],
            'items'     => $purchaseItems,
            'chart'     => Graficas::get_grafica(
                route('admin.company.brand.metrics.sales.ajax', [
                    'company' => $company,
                    'brand'   => $brand,
                ]),
                array(
                    'id'           => 'salesLocation',
                    'titulo'       => '',
                    'tipo'         => 'LineChart',
                    'extra_charts' => '["LineChart","SteppedAreaChart","AreaChart","ColumnChart","BarChart", "Table"]',
                    'filters'      => VistasGafaFit::view('admin.brand.metrics.sales.filters')->render(),
                    'options'      => 'vAxis: {textPosition: \'in\',format:\'' . $currency . '#,###\'},',
                )),
        ]);

    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function purchaseByPaymentTypes(Request $request, Company $company, Brand $brand)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogSalesByPaymentType::class));
        }
    }

    public function sales(Request $request, Company $company, Brand $brand)
    {
        $start = $request->has('start') && $request->get('start') !== null ? new Carbon($request->get('start'), $brand->getTimezone()) : $brand->now()->startOfWeek();
        $end = $request->has('end') && $request->get('end') !== null ? new Carbon($request->get('end'), $brand->getTimezone()) : $brand->now()->endOfWeek();
        $grouped = $request->get('grouped') ?? 'day';
        $currencies_id = $request->get('currency', 0);
        $location_ids = $request->get('locations', []);
        $locations = $brand->locations()->when($location_ids,function ($q,$locations){
            $q->whereIn('id',$locations);
        })->get();

        $metrics = new Sales($start, $end, $grouped, $currencies_id);
        $metrics->setLocations($locations);

        return $metrics->getData();
    }
}
