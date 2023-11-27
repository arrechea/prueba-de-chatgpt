<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 23/07/2018
 * Time: 03:54 PM
 */

namespace App\Librerias\Metrics;


use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\Reservation\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LibReservations
{
    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     *
     * @return string
     */
    public static function getReservationsByRoom(Request $request, Company $company, Brand $brand, Location $location)
    {
        $filters = collect($request->get('filters'))->mapWithKeys(function ($item) {
            return [$item['name'] => $item['value']];
        });;
        $start = $filters['start'] ?? null;
        $end = $filters['end'] ?? null;

        if (!$start || !$end)
            abort(400);

        $reservations = Reservation::with('room')
            ->select(DB::raw('count(*) as reservations,rooms_id'))
            ->where(function ($q) use ($start, $end, $location) {
            $q->where('meeting_start', '>=', $start);
            $q->where('meeting_start', '<=', $end);
            $q->where('locations_id', $location->id);
        })->groupBy('rooms_id')->get();

        $reservations = $reservations->map(function ($item) {
            $item->room_name = $item->room->name;

            return $item;
        });

        return '{"data":' . $reservations->values()->toJson() . '}';
    }
}
