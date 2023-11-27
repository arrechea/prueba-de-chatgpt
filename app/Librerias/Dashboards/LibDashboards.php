<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 02/07/2018
 * Time: 03:21 PM
 */

namespace App\Librerias\Dashboards;

use App\Models\Brand\Brand;
use App\Models\Location\Location;
use App\Models\Purchase\Purchase;
use App\Models\Reservation\Reservation;
use Carbon\Carbon;

class LibDashboards
{

    /**
     * Reservaciones del mes en curso que no esten canceladas y que sean del location respectivo
     *
     * @param Location|null $location
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function reservationsMonth(Location $location)
    {
        $primer_dia = (new Carbon('first day of this month'))->toDateString();
        $ultimo_dia = (new Carbon('last day of this month'))->toDateString();

        $reservationsMonth = Reservation::where('cancelled', false)->where('locations_id', $location->id)->whereBetween('meeting_start', [$primer_dia, $ultimo_dia]);

        return $reservationsMonth->count();

    }


    /**
     * Devulve la suma de todas las compras de el mes en curso.
     *
     * @param $location
     *
     * @return mixed
     */
    public static function purchasesMonth(Location $location)
    {

        $primer_dia = (new Carbon('first day of this month'))->toDateString();
        $ultimo_dia = (new Carbon('last day of this month'))->toDateString();

        $purchasesMonth = Purchase::where('locations_id', $location->id)->where('status', 'complete')->whereBetween('created_at', [$primer_dia, $ultimo_dia]);


        return $purchasesMonth->sum('total');
    }

    /**
     * Reservas del mes actual en la marca
     *
     * @param $brand
     *
     * @return mixed
     */
    public static function reservationsBrandsMonth(Brand $brand)
    {
        $primer_dia = (new Carbon('first day of this month'))->toDateString();
        $ultimo_dia = (new Carbon('last day of this month'))->toDateString();

        $reservationsMonth = Reservation::where('cancelled', false)->where('brands_id', $brand->id)->whereBetween('meeting_start', [$primer_dia, $ultimo_dia]);

        return $reservationsMonth->count();

    }

    /**
     * Compras de el mes actual en la marca.
     *
     * @param Brand $brand
     *
     * @return mixed
     */
    public static function purchasesBrandMonth(Brand $brand)
    {

        $primer_dia = (new Carbon('first day of this month'))->toDateString();
        $ultimo_dia = (new Carbon('last day of this month'))->toDateString();

        $purchasesMonth = Purchase::where('brands_id', $brand->id)->where('status', 'complete')->whereBetween('created_at', [$primer_dia, $ultimo_dia]);


        return $purchasesMonth->sum('total');
    }

}
