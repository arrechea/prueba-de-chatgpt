<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 20/07/2018
 * Time: 10:10 AM
 */

namespace App\Http\Controllers\Admin\Location\Metrics;


use App\Admin;
use App\Http\Controllers\Admin\Location\LocationLevelController;
use App\Http\Controllers\AdminController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Location\Metrics\CatalogReservationsTotal;
use App\Librerias\Chart\Graficas;
use App\Librerias\Metrics\LibReservations;
use App\Librerias\Metrics\Reservations\LocationOccupationMetrics;
use App\Librerias\Metrics\Reservations\LocationTotalsMetrics;
use App\Librerias\Metrics\ReservationsMetrics;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Http\Requests\AdminRequest as Request;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReservationsMetricsController extends LocationLevelController
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
    public function index(Request $request, Company $company, Brand $brand, Location $location)
    {
        return VistasGafaFit::view('admin.location.metrics.reservations.chart', [
            'chart'         => Graficas::get_grafica(
                route('admin.company.brand.locations.metrics.reservations.ajax', [
                    'company'  => $company,
                    'brand'    => $brand,
                    'location' => $location,
                ]),
                array(
                    'id'           => 'reservationsLocation',
                    'titulo'       => __('metrics.metrics-location'),
                    'tipo'         => 'LineChart',
                    'filters'      => VistasGafaFit::view('admin.location.metrics.reservations.filters')->render(),
                    'extra_charts' => '["LineChart","SteppedAreaChart","AreaChart","ColumnChart","BarChart","Table"]',
                )),
            'catalogo'      => new CatalogReservationsTotal(),
            'ajaxDatatable' => route('admin.company.brand.locations.metrics.reservations.rooms', ['company' => $company, 'brand' => $brand, 'location' => $location]),
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
    public function reservations(Request $request, Company $company, Brand $brand, Location $location)
    {
        $now = $brand->now();
        $start = $request->get('start');
        $start = $start ? new Carbon($start, $brand->getTimezone()) : $now->startOfWeek();
        $end = $request->get('end');
        $end = $end ? new Carbon($end, $brand->getTimezone()) : $now->endOfWeek();
        $grouped = $request->get('grouped') ?? 'day';
        $week_day = $request->get('week_day', []);

        $metrics = new LocationTotalsMetrics($start, $end, $grouped, $week_day);
        $metrics->setSets(collect([$location]));

//        $metrics->setSets(Location::all());

        return $metrics->getData();
    }

    public function compareReservations(Request $request, Company $company, Brand $brand, Location $location)
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

            $metrics = new LocationTotalsMetrics($start, $end, $grouped, $week_day);
            $metrics->setSets(collect([$location]));

            return $metrics->getData();
        }

        return null;
    }

    /**
     * Obtiene las reservaciones por salón
     *
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     *
     * @return string
     */
    public function reservationsByRoom(Request $request, Company $company, Brand $brand, Location $location)
    {
        return response()->json(CatalogFacade::dataTableIndex($request, CatalogReservationsTotal::class));
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
    public function location(Request $request, Company $company, Brand $brand, Location $location)
    {
        return VistasGafaFit::view('admin.location.metrics.reservations.location.location-chart', [
            'chart'                    => Graficas::get_grafica(
                route('admin.company.brand.locations.metrics.reservations.location.ajax', [
                    'company'  => $company,
                    'brand'    => $brand,
                    'location' => $location,
                ]),
                array(
                    'id'           => 'locationOccupation',
                    'titulo'       => __('metrics.occupation-percentage'),
                    'tipo'         => 'LineChart',
                    'filters'      => VistasGafaFit::view('admin.location.metrics.reservations.location.filters')->render(),
                    'extra_charts' => '["LineChart","SteppedAreaChart","AreaChart","ColumnChart","BarChart","Table"]',
                )),
            'compare_occupation_chart' => Graficas::get_grafica(
                route('admin.company.brand.locations.metrics.reservations.location.compare.ajax', [
                    'company'  => $company,
                    'brand'    => $brand,
                    'location' => $location,
                ]),
                array(
                    'id'            => 'compareLocationOccupation',
                    'titulo'        => __('metrics.compare-occupation-percentage'),
                    'tipo'          => 'LineChart',
                    'other_filters' => 'ChartDashboard--filters--locationOccupation',
                    'extra_charts'  => '["LineChart","SteppedAreaChart","AreaChart","ColumnChart","BarChart","Table"]',
                )),
            'chart_totals'             => Graficas::get_grafica(
                route('admin.company.brand.locations.metrics.reservations.ajax', [
                    'company'  => $company,
                    'brand'    => $brand,
                    'location' => $location,
                ]),
                array(
                    'id'            => 'locationTotals',
                    'titulo'        => __('metrics.reservations-totals'),
                    'tipo'          => 'LineChart',
                    'other_filters' => 'ChartDashboard--filters--locationOccupation',
                    'extra_charts'  => '["LineChart","SteppedAreaChart","AreaChart","ColumnChart","BarChart","Table"]',
                )),
            'compare_totals_chart'     => Graficas::get_grafica(
                route('admin.company.brand.locations.metrics.reservations.compare.ajax', [
                    'company'  => $company,
                    'brand'    => $brand,
                    'location' => $location,
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
    public function locationData(Request $request, Company $company, Brand $brand, Location $location)
    {
        $now = $brand->now();
        $start = $request->get('start');
        $start = $start ? new Carbon($start, $brand->getTimezone()) : $now->startOfWeek();
        $end = $request->get('end');
        $end = $end ? new Carbon($end, $brand->getTimezone()) : $now->endOfWeek();
        $grouped = $request->get('grouped') ?? 'day';
        $week_day = $request->get('week_day', []);

        $metrics = new LocationOccupationMetrics($start, $end, $grouped, $week_day);
        $metrics->setSets(collect([$location]));

        return $metrics->getData();
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     *
     * @return \Illuminate\Http\JsonResponse|null
     */
    public function compareLocationData(Request $request, Company $company, Brand $brand, Location $location)
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

            $metrics = new LocationOccupationMetrics($start, $end, $grouped, $week_day);
            $metrics->setSets(collect([$location]));

            return $metrics->getData();
        }

        return null;
    }
}
