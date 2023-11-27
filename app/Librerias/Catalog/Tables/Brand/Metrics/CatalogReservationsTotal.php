<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 30/07/2018
 * Time: 10:24 AM
 */

namespace App\Librerias\Catalog\Tables\Brand\Metrics;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Catalog\Tables\Location\RoomRelationship;
use App\Librerias\Helpers\LibFilters;
use App\Models\Location\locationsRelationship;
use App\Models\Reservation\Reservation;
use App\Models\Reservation\ReservationsRelationship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogReservationsTotal extends LibCatalogoModel
{
    use locationsRelationship;
    protected $table = 'locations';


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
            new LibValoresCatalogo($this, __('metrics.Locations'), 'name'),
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
            return $q->whereIn('id', $locations);
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
