<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 01/08/2018
 * Time: 10:07 AM
 */

namespace App\Librerias\Metrics\Reservations;


use App\Librerias\Helpers\Carbon;
use App\Librerias\Metrics\ChartDataByDate;
use App\Models\Meeting\Meeting;
use App\Models\Reservation\Reservation;
use Illuminate\Support\Facades\DB;

class LocationOccupationMetrics extends ChartDataByDate
{
    protected $week_days;

    public function __construct(\Carbon\Carbon $start, \Carbon\Carbon $end, string $grouped = 'day', array $week_days = [])
    {
        parent::__construct($start, $end, $grouped);

        $this->week_days = $week_days;
    }

    protected function getQuery()
    {
        $reservations = Reservation::selectRaw('count(*) reservations_count,meetings_id')->whereRaw('cancelled = 0')->groupBy('meetings_id');
        $query = Meeting::leftJoin(DB::raw('(' . $reservations->toSql() . ') as res'), 'res.meetings_id', 'meetings.id');

        return $query;
    }

    protected function getRelationColumn()
    {
        return 'locations_id';
    }

    protected function getSearchModel()
    {
        return Meeting::class;
    }

    protected function getDateColumn()
    {
        return 'start_date';
    }

    protected function getAdditionFunction()
    {
        return 'capacity';
    }

    protected function getSumSql()
    {
        return 'ifnull(if(
        sum(case when locations_id=$id then capacity else 0 end)>0,
        (sum(case when locations_id=$id then reservations_count else 0 end)/sum(case when locations_id=$id then capacity else 0 end))*100,
        0
        ),0) "$name"';
    }

    protected function wheres($q)
    {
        parent::wheres($q);
        if (!!$this->week_days)
            $q->whereIn(DB::raw('weekday(start_date)'), $this->week_days);
    }
}
