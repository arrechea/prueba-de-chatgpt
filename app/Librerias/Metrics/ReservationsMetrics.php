<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 19/07/2018
 * Time: 10:40 AM
 */

namespace App\Librerias\Metrics;


use App\Librerias\Chart\Cell;
use App\Librerias\Chart\Column;
use App\Librerias\Chart\ColumnCollection;
use App\Http\Requests\AdminRequest as Request;
use App\Librerias\Chart\Graficas;
use App\Librerias\Chart\Row;
use App\Librerias\Chart\RowCollection;
use App\Models\Location\Location;
use App\Models\Reservation\Reservation;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReservationsMetrics
{
    private $grouped = 'day';
    private $locations;
    private $columns;
    private $start;
    private $end;
    private $select_sql;
    private $group_sql;

    const ALLOWED_GROUPINGS = [
        'day',
        'hour',
        'month',
    ];
    const LOCATION_SUM_SQL = 'sum(case when locations_id = $id then 1 else 0 end) "$name"';

    public function __construct(Carbon $start, Carbon $end, string $grouped = 'day')
    {
        $this->setGrouped($grouped);
        $this->start = $start;
        $this->end = $end;
    }

    private function setGrouped(string $grouped)
    {
        $this->grouped = in_array($grouped, self::ALLOWED_GROUPINGS) ? $grouped : 'day';
    }

    public function setLocations(Collection $locations)
    {
        $this->locations = $locations->sortBy('order')->sortBy('name');
    }

    private function getSql()
    {
        if ($this->locations) {
            $this->columns = new ColumnCollection();
            $this->columns->addColumn(new Column('date', __('charts.date'), 'string'));
            $select = '';
            $date_group = 'year(meeting_start),month(meeting_start)';
            $date_select = 'date_format(min(meeting_start),"%Y-%m';
            switch ($this->grouped) {
                case 'day':
                    $date_select .= '-%d") date';
                    $date_group .= ',day(meeting_start)';
                    break;
                case 'hour':
                    $date_select .= '-%d %k:00") date';
                    $date_group .= ',day(meeting_start),hour(meeting_start)';
                    break;
                case 'month':
                    $date_select .= '") date';
                    $date_group .= '';
                    break;
            }

            $select .= $date_select;

            $locations = $this->locations;
            foreach ($locations as $location) {
                $location_sum = str_replace(['$id', '$name'], [(string)$location->id, $location->name], self::LOCATION_SUM_SQL);
                $select .= ',' . $location_sum;
                $this->columns->addColumn(new Column($location->id, $location->name, 'number'));
            }

            $this->select_sql = $select;
            $this->group_sql = $date_group;
        }
    }

    public function getData()
    {
        if (!$this->start || !$this->end || !$this->locations) {
            return null;
        }

        $this->getSql();

        $data = Reservation::select(DB::raw($this->select_sql))->where(function ($q) {
            $q->where('cancelled', false);
            $q->whereDate('meeting_start', '<=', $this->end);
            $q->whereDate('meeting_start', '>=', $this->start);
        })->groupBy(DB::raw($this->group_sql))->get();

        $columns = $this->columns;
        $rows = new RowCollection();
        foreach ($data as $datum) {
            $row = new Row();
            $row->addCell(new Cell($datum->date));
            foreach ($this->locations as $location) {
                $row->addCell(new Cell($datum[ $location->name ]));
            }
            $rows->addRow($row);
        }

        return response()->json(
            Graficas::parseAjaxInfo($columns, $rows)
        );
    }


    //        $dummyData = [
//            [
//                'day'          => 'lunes',
//                'locations_id' => 2,
//                'total'        => 20,
//            ],
//            [
//                'day'          => 'martes',
//                'locations_id' => 2,
//                'total'        => 20,
//            ],
//        ];
//
//        $preRows = [];
//
//        foreach ($dummyData as $line) {
//            $day = $line['day'] ?? '';
//            $locations_id = $line['locations_id'] ?? '';
//            $total = $line['total'] ?? '';
//
//            if (!isset($preRows[ $day ])) {
//                $preRows[ $day ] = [];
//            }
//            $preRows[ $day ][ $locations_id ] = $total;
//        }
//        [
//            213213123 => [
//                'label'  => 'martes',
//                'values' => [
//                    1 => 20,
//                    2 => 20,
//                ],
//            ],
//            'lunes'   => [
//                2 => 20,
//            ],
//        ];
//
//        [
//            'day' => 'martes',
//            'UB1' => 1,
//            'UB2' => 20,
//        ];
}
