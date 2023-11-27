<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 01/08/2018
 * Time: 10:01 AM
 */

namespace App\Librerias\Metrics;


use App\Librerias\Chart\Cell;
use App\Librerias\Chart\Column;
use App\Librerias\Chart\ColumnCollection;
use App\Librerias\Chart\Graficas;
use App\Librerias\Chart\Row;
use App\Librerias\Chart\RowCollection;
use Carbon\Carbon;
use function GuzzleHttp\Promise\all;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

abstract class ChartDataByDate
{
    protected $grouped = 'day';
    protected $sets;
    protected $columns;
    protected $start;
    protected $end;
    protected $select_sql;
    protected $group_sql;
    //Agrupamientos permitidos
    protected $allowed_groupings = [
        'day',
        'hour',
        'month',
    ];

    const SUM_SQL = 'sum(case when $column = $id then $add else 0 end) "$name"';


    public function __construct(Carbon $start, Carbon $end, string $grouped = 'day')
    {
        $this->setGrouped($grouped);
        $this->start = $start;
        $this->end = $end;
    }

    protected abstract function getRelationColumn();

    protected abstract function getSearchModel();

    protected function getDateColumn()
    {
        return 'created_at';
    }

    protected function getNameColumn()
    {
        return 'name';
    }

    protected function getAdditionFunction()
    {
        return '1';
    }

    protected function getSumSql()
    {
        $column = $this->getRelationColumn();
        $add = $this->getAdditionFunction();

        return str_replace(['$add', '$column'], [$add, $column], self::SUM_SQL);
    }

    protected function setGrouped(string $grouped)
    {
        $this->grouped = in_array($grouped, $this->allowed_groupings) ? $grouped : 'day';
    }

    public final function setSets(Collection $sets, $order_column = false)
    {
        $name = $this->getNameColumn();
        $sets = $order_column ? $sets->sortBy('order') : $sets;

        $this->sets = $sets->sortBy('order')->sortBy($name);
    }

    protected function getDateSelect()
    {
        $date_select = 'date_format(min(' . $this->getDateColumn() . '),"%Y-%m';
        switch ($this->grouped) {
            case 'day':
                $date_select .= '-%d") date';
                break;
            case 'hour':
                $date_select .= '-%d %k:00") date';
                break;
            case 'month':
                $date_select .= '") date';
                break;
        }

        return $date_select;
    }

    protected function getDateGroupBy()
    {
        $date = $this->getDateColumn();

        $date_group = "year({$date}),month({$date})";

        switch ($this->grouped) {
            case 'day':
                $date_group .= ",day({$date})";
                break;
            case 'hour':
                $date_group .= ",day({$date}),hour({$date})";
                break;
            case 'month':
                $date_group .= '';
                break;
        }

        return $date_group;
    }

    private final function getSql()
    {
        if ($this->sets) {
            $this->columns = new ColumnCollection();
            $this->columns->addColumn(new Column('date', __('charts.date'), 'string'));

            $select = $this->getDateSelect();
            $date_group = $this->getDateGroupBy();

            $sets = $this->sets;
            foreach ($sets as $set) {
                $sum_sql = $this->getSumSql();
                $name = $this->getNameColumn();
                $location_sum = str_replace(['$id', '$name'], [(string)$set->id, $set->$name], $sum_sql);
                $select .= ',' . $location_sum;
                $this->columns->addColumn(new Column($set->id, $set->$name, 'number'));
            }

            $this->select_sql = $select;
            $this->group_sql = $date_group;
        }
    }

    private final function getDataBuilder()
    {
        $this->getSql();
        $where = function ($q) {
            $this->wheres($q);
        };
        $model = $this->getQuery();
        $data = $model->selectRaw($this->select_sql)->where($where)->groupBy(DB::raw($this->group_sql));

        return $data;
    }

    protected function getQuery()
    {
        $model = $this->getSearchModel();

        return $model::query();
    }

    protected function wheres($q)
    {
        $date = $this->getDateColumn();
        $q->whereDate($date, '<=', $this->end);
        $q->whereDate($date, '>=', $this->start);
    }

    public final function getData()
    {
        if (!$this->start || !$this->end || !$this->sets) {
            return null;
        }

//        dd($this->getQuery()->where(DB::raw('hour(start_date)'),'>=','8')->orderBy(DB::raw('hour(start_date)'))->get());
        $data = $this->getDataBuilder()->get();

        $columns = $this->columns;
        $rows = new RowCollection();
        foreach ($data as $datum) {
            $row = new Row();
            $row->addCell(new Cell($datum->date));
            foreach ($this->sets as $set) {
                $name = $this->getNameColumn();
                $row->addCell(new Cell($datum[ $set->$name ]));
            }
            $rows->addRow($row);
        }

        return response()->json(
            Graficas::parseAjaxInfo($columns, $rows)
        );
    }
}
