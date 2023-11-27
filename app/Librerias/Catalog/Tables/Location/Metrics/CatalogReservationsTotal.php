<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 30/07/2018
 * Time: 10:24 AM
 */

namespace App\Librerias\Catalog\Tables\Location\Metrics;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Catalog\Tables\Location\RoomRelationship;
use App\Librerias\Helpers\LibFilters;
use App\Models\Reservation\Reservation;
use App\Models\Reservation\ReservationsRelationship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogReservationsTotal extends LibCatalogoModel
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
            new LibValoresCatalogo($this, __('metrics.reservations'), 'reservations_count'),
        ];
    }

    protected static function filtrarQueries(&$query)
    {
        $start = LibFilters::getFilterValue('start');
        $end = LibFilters::getFilterValue('end');
        $location = LibFilters::getFilterValue('locations_id');
        if (!$start || !$end)
            abort(400);

        $query->withCount(['reservations' => function ($q) use ($start, $end, $location) {
            $q->whereDate('meeting_start', '>=', $start);
            $q->whereDate('meeting_start', '<=', $end);
            $q->where('locations_id', $location);
            $q->where('cancelled', 0);
        }]);

        $query->whereHas('reservations', function ($q) use ($start, $end, $location) {
            $q->whereDate('meeting_start', '>=', $start);
            $q->whereDate('meeting_start', '<=', $end);
            $q->where('locations_id', $location);
            $q->where('cancelled', 0);
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
