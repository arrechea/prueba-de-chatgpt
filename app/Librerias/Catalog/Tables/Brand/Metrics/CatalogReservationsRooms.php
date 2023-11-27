<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 20/11/2018
 * Time: 11:08
 */

namespace App\Librerias\Catalog\Tables\Brand\Metrics;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Catalog\Tables\Location\RoomRelationship;
use App\Librerias\Helpers\LibFilters;
use App\Models\Location\Location;
use App\Models\Room\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogReservationsRooms extends LibCatalogoModel
{
    use RoomRelationship;
    protected $table = 'rooms';


    public function link(): string
    {
        return '';
    }

    public function GetName()
    {
        return 'Reservations Totals';
    }

    public function Valores(Request $request = null)
    {
        return [
            new LibValoresCatalogo($this, __('metrics.room'), 'name'),
            new LibValoresCatalogo($this, __('metrics.Location'), 'location'),
            new LibValoresCatalogo($this, __('metrics.reservations'), 'reservations_count'),
        ];
    }

    protected static function filtrarQueries(&$query)
    {
        $start = LibFilters::getFilterValue('start');
        $end = LibFilters::getFilterValue('end');
        $brands_id = LibFilters::getFilterValue('brands_id');
        $locations = LibFilters::getFilterValue('locations', null, []);
        if (!$start || !$end)
            abort(400);

        $location_query = Location::selectRaw('id location_id,name location');

        $location_query_sql = $location_query->toSql();
        foreach ($location_query->getBindings() as $binding) {
            $value = is_numeric($binding) ? $binding : "'" . $binding . "'";
            $location_query_sql = preg_replace('/\?/', $value, $location_query_sql, 1);
        }

        $query->leftJoin(DB::raw('(' . $location_query_sql . ') loc'), 'loc.location_id', 'rooms.locations_id');

        $query->select('id', 'name', 'location');

        $query->withCount(['reservations' => function ($q) use ($start, $end, $brands_id) {
            $q->whereDate('meeting_start', '>=', $start);
            $q->whereDate('meeting_start', '<=', $end);
            $q->where('brands_id', $brands_id);
            $q->where('cancelled', 0);
        }]);

        $query->whereHas('reservations', function ($q) use ($start, $end, $brands_id) {
            $q->whereDate('meeting_start', '>=', $start);
            $q->whereDate('meeting_start', '<=', $end);
            $q->where('brands_id', $brands_id);
            $q->where('cancelled', 0);
        });

        $query->when($locations, function ($q, $locations) {
            return $q->whereIn('locations_id', $locations);
        });
    }

    public function GetOtherFilters()
    {
        return 'reservations-metrics-filter';
    }

    public function GetSearchable()
    {
        return false;
    }

    protected static function QueryToOrderBy()
    {
        return 'reservations_count';
    }

    protected static function QueryToOrderByOrder()
    {
        return 'desc';
    }

    public function GetAllowStatusSelector()
    {
        return false;
    }
}
