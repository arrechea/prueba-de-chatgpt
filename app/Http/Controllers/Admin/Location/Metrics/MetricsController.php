<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 18/07/2018
 * Time: 12:11 PM
 */

namespace App\Http\Controllers\Admin\Location\Metrics;


use App\Http\Controllers\Controller;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Location\Metrics\CatalogNewUsers;
use App\Librerias\Catalog\Tables\Location\Metrics\CatalogTopUser;
use App\Librerias\Chart\Cell;
use App\Librerias\Chart\Column;
use App\Librerias\Chart\ColumnCollection;
use App\Librerias\Chart\Graficas;
use App\Librerias\Chart\Locations\Sales;
use App\Librerias\Chart\Row;
use App\Librerias\Chart\RowCollection;
use App\Http\Requests\AdminRequest as Request;
use App\Librerias\Helpers\LibRoute;
use App\Librerias\Metrics\LibReservations;
use App\Librerias\Metrics\ReservationsMetrics;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\Purchase\Purchase;
use App\Models\Reservation\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MetricsController extends Controller
{

    public function sales(Request $request, Company $company, Brand $brand, Location $location)
    {
        $start = $request->has('start') && $request->get('start') !== null ? new Carbon($request->get('start'), $brand->getTimezone()) : $brand->now()->startOfWeek();
        $end = $request->has('end') && $request->get('end') !== null ? new Carbon($request->get('end'), $brand->getTimezone()) : $brand->now()->endOfWeek();
        $grouped = $request->get('grouped') ?? 'day';
        $currencies_id = $request->get('currency', 0);

        $metrics = new Sales($start, $end, $grouped,$currencies_id);
        $metrics->setLocations(collect([$location]));

        return $metrics->getData();

    }
}
