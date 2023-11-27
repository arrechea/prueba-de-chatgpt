<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 17/07/2018
 * Time: 05:27 PM
 */

namespace App\Librerias\Chart\Locations;


use App\Librerias\Chart\Cell;
use App\Librerias\Chart\Column;
use App\Librerias\Chart\ColumnCollection;
use App\Librerias\Chart\Graficas;
use App\Librerias\Chart\Row;
use App\Librerias\Chart\RowCollection;
use App\Librerias\Dashboards\LibDashboards;
use App\Librerias\Metrics\ChartDataByDate;
use App\Models\Location\Location;
use App\Models\Purchase\Purchase;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Sales
{
    private $grouped = 'day';
    private $locations;
    private $columns;
    private $start;
    private $end;
    private $select_sql;
    private $group_sql;
    private $currencies_id;

    const ALLOWED_GROUPINGS = [
        'day',
        'hour',
        'month',
    ];


    const LOCATION_SUM_SQL = 'sum(case when locations_id = $id then total else 0 end) "$name"';

    public function __construct(Carbon $start, Carbon $end, string $grouped = 'day', int $currencies_id = 0)
    {
        $this->setGrouped($grouped);
        $this->start = $start;
        $this->end = $end;
        $this->currencies_id = (int)$currencies_id;
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
            $this->columns->addColumn(new Column('date', __('metrics.date'), 'string'));
            $select = '';
            $date_group = 'year(created_at),month(created_at)';
            $date_select = 'date_format(min(created_at),"%Y-%m';
            switch ($this->grouped) {
                case 'day':
                    $date_select .= '-%d") date';
                    $date_group .= ',day(created_at)';
                    break;
                case 'hour':
                    $date_select .= '-%d %k:00") date';
                    $date_group .= ',day(created_at),hour(created_at)';
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

        $data = Purchase::select(DB::raw($this->select_sql))->where(function ($q) {
            $q->where('status', 'complete');
            $q->whereDate('created_at', '<=', $this->end);
            $q->whereDate('created_at', '>=', $this->start);
            $q->where('currencies_id', $this->currencies_id);
        })->whereHas('payment_type', function ($q) {
            $q->where('slug', '!=', 'courtesy');
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


}
