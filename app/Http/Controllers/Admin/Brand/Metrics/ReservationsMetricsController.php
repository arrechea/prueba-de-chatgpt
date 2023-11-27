<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 20/07/2018
 * Time: 10:10 AM
 */

namespace App\Http\Controllers\Admin\Brand\Metrics;


use App\Admin;
use App\Http\Controllers\Admin\Brand\BrandLevelController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Brand\Metrics\CatalogReservationsRooms;
use App\Librerias\Catalog\Tables\Brand\Metrics\CatalogReservationsTotal;
use App\Librerias\Chart\Graficas;
use App\Librerias\Metrics\Reservations\LocationOccupationMetrics;
use App\Librerias\Metrics\Reservations\LocationTotalsMetrics;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Http\Requests\AdminRequest as Request;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReservationsMetricsController extends BrandLevelController
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
     * Vista de métricas de reservaciones.
     *
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(Request $request, Company $company, Brand $brand)
    {
        return VistasGafaFit::view('admin.brand.metrics.reservations.chart', [
            'chart'               => Graficas::get_grafica(
                route('admin.company.brand.metrics.reservations.ajax', [
                    'company' => $company,
                    'brand'   => $brand,
                ]),
                array(
                    'id'           => 'reservationsLocation',
                    'titulo'       => __('metrics.reservations.MetricsByLocation'),
                    'tipo'         => 'LineChart',
                    'filters'      => VistasGafaFit::view('admin.brand.metrics.reservations.filters')->render(),
                    'extra_charts' => '["LineChart","SteppedAreaChart","AreaChart","ColumnChart","BarChart","Table"]',
                )),
            'catalogTotals'       => new CatalogReservationsTotal(),
            'ajaxDatatableTotals' => route('admin.company.brand.metrics.reservations.totals', ['company' => $company, 'brand' => $brand]),
            'catalogRooms'        => new CatalogReservationsRooms(),
            'ajaxDatatableRooms'  => route('admin.company.brand.metrics.reservations.rooms', ['company' => $company, 'brand' => $brand]),
        ]);
    }

    /**
     * Obtiene las reservaciones usadas para los charts.
     *
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     *
     * @return \Illuminate\Http\JsonResponse|null
     */
    public function reservations(Request $request, Company $company, Brand $brand)
    {
        $now = $brand->now();
        $start = $request->get('start');
        $start = $start ? new Carbon($start, $brand->getTimezone()) : $now->startOfWeek();
        $end = $request->get('end');
        $end = $end ? new Carbon($end, $brand->getTimezone()) : $now->endOfWeek();
        $grouped = $request->get('grouped') ?? 'day';
        $week_day = $request->get('week_day', []);
        $location_ids = $request->get('locations', []);
        $locations = $brand->locations()->when($location_ids, function ($q) use ($location_ids) {
            $q->whereIn('id', $location_ids);
        })->get();

        $metrics = new LocationTotalsMetrics($start, $end, $grouped, $week_day);
        $metrics->setSets($locations);

        return $metrics->getData();
    }

    public function compareReservations(Request $request, Company $company, Brand $brand)
    {
        $now = $brand->now();
        $compare = $request->get('compare', false);
        if (!!$compare && $compare === 'on') {
            $start = $request->get('compare_start');
            $start = $start ? new Carbon($start, $brand->getTimezone()) : $now->startOfWeek()->subYear();
            $end = $request->get('compare_end');
            $end = $end ? new Carbon($end, $brand->getTimezone()) : $now->endOfWeek()->subYear();
            $grouped = $request->get('grouped') ?? 'day';
            $week_day = $request->get('week_day', []);
            $location_ids = $request->get('locations', []);
            $locations = $brand->locations()->when($location_ids, function ($q) use ($location_ids) {
                $q->whereIn('id', $location_ids);
            })->get();

            $metrics = new LocationTotalsMetrics($start, $end, $grouped, $week_day);
            $metrics->setSets($locations);

            return $metrics->getData();
        }

        return null;
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reservationsTotals(Request $request, Company $company, Brand $brand)
    {
        return response()->json(CatalogFacade::dataTableIndex($request, CatalogReservationsTotal::class));
    }

    public function reservationsByRoom(Request $request, Company $company, Brand $brand)
    {
        return response()->json(CatalogFacade::dataTableIndex($request, CatalogReservationsRooms::class));
    }

    /**
     * Vista de reservaciones en la ubicación
     *
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function profitability(Request $request, Company $company, Brand $brand)
    {
        return VistasGafaFit::view('admin.brand.metrics.profitability.chart', [
            'chart'                    => Graficas::get_grafica(
                route('admin.company.brand.metrics.reservations.profitability.ajax', [
                    'company' => $company,
                    'brand'   => $brand,
                ]),
                array(
                    'id'           => 'locationOccupation',
                    'titulo'       => __('metrics.occupation-percentage'),
                    'tipo'         => 'LineChart',
                    'filters'      => VistasGafaFit::view('admin.brand.metrics.profitability.filters')->render(),
                    'extra_charts' => '["LineChart","SteppedAreaChart","AreaChart","ColumnChart","BarChart","Table"]',
                )),
            'compare_occupation_chart' => Graficas::get_grafica(
                route('admin.company.brand.metrics.reservations.compare.ajax', [
                    'company' => $company,
                    'brand'   => $brand,
                ]),
                array(
                    'id'            => 'compareLocationOccupation',
                    'titulo'        => __('metrics.compare-occupation-percentage'),
                    'tipo'          => 'LineChart',
                    'other_filters' => 'ChartDashboard--filters--locationOccupation',
                    'extra_charts'  => '["LineChart","SteppedAreaChart","AreaChart","ColumnChart","BarChart","Table"]',
                )),
            'chart_totals'             => Graficas::get_grafica(
                route('admin.company.brand.metrics.reservations.ajax', [
                    'company' => $company,
                    'brand'   => $brand,
                ]),
                array(
                    'id'            => 'locationTotals',
                    'titulo'        => __('metrics.reservations-totals'),
                    'tipo'          => 'LineChart',
                    'other_filters' => 'ChartDashboard--filters--locationOccupation',
                    'extra_charts'  => '["LineChart","SteppedAreaChart","AreaChart","ColumnChart","BarChart","Table"]',
                )),
            'compare_totals_chart'     => Graficas::get_grafica(
                route('admin.company.brand.metrics.reservations.compare.ajax', [
                    'company' => $company,
                    'brand'   => $brand,
                ]),
                array(
                    'id'            => 'compareLocationTotals',
                    'titulo'        => __('metrics.compare-reservations-totals'),
                    'tipo'          => 'LineChart',
                    'other_filters' => 'ChartDashboard--filters--locationOccupation',
                    'extra_charts'  => '["LineChart","SteppedAreaChart","AreaChart","ColumnChart","BarChart","Table"]',
                )),
        ]);
    }

    /**
     * Datos de ubicación
     *
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     *
     * @return \Illuminate\Http\JsonResponse|null
     */
    public function profitabilityData(Request $request, Company $company, Brand $brand)
    {
        $now = $brand->now();
        $start = $request->get('start');
        $start = $start ? new Carbon($start, $brand->getTimezone()) : $now->startOfWeek();
        $end = $request->get('end');
        $end = $end ? new Carbon($end, $brand->getTimezone()) : $now->endOfWeek();
        $grouped = $request->get('grouped') ?? 'day';
        $week_day = $request->get('week_day', []);
        $location_ids = $request->get('locations', []);
        $locations = $brand->locations()->when($location_ids, function ($q) use ($location_ids) {
            $q->whereIn('id', $location_ids);
        })->get();

        $metrics = new LocationOccupationMetrics($start, $end, $grouped, $week_day);
        $metrics->setSets($locations);

        return $metrics->getData();
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Http\JsonResponse|null
     */
    public function compareData(Request $request, Company $company, Brand $brand)
    {
        $now = $brand->now();
        $compare = $request->get('compare', false);
        if (!!$compare && $compare === 'on') {
            $start = $request->get('compare_start');
            $start = $start ? new Carbon($start, $brand->getTimezone()) : $now->startOfWeek()->subYear();
            $end = $request->get('compare_end');
            $end = $end ? new Carbon($end, $brand->getTimezone()) : $now->endOfWeek()->subYear();
            $grouped = $request->get('grouped') ?? 'day';
            $week_day = $request->get('week_day', []);
            $location_ids = $request->get('locations', []);
            $locations = $brand->locations()->when($location_ids, function ($q) use ($location_ids) {
                $q->whereIn('id', $location_ids);
            })->get();

            $metrics = new LocationOccupationMetrics($start, $end, $grouped, $week_day);
            $metrics->setSets($locations);

            return $metrics->getData();
        }

        return null;
    }
}
