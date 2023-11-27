<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 01/08/2018
 * Time: 04:06 PM
 */

namespace App\Librerias\Metrics\Reservations;


use App\Librerias\Metrics\ChartDataByDate;
use App\Models\Reservation\Reservation;

class LocationTotalsMetrics extends LocationOccupationMetrics
{
    protected function getSumSql()
    {
        return 'ifnull(sum(case when locations_id = $id then reservations_count else 0 end),0) "$name"';
    }
}
