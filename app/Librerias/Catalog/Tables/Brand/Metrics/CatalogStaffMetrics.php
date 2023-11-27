<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 24/07/2018
 * Time: 03:18 PM
 */

namespace App\Librerias\Catalog\Tables\Brand\Metrics;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Catalog\Tables\Location\Reservations\StaffRelationship;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Meeting\Meeting;
use App\Models\Reservation\Reservation;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CatalogStaffMetrics extends LibCatalogoModel
{

    use SoftDeletes, StaffRelationship;
    protected $table = 'staff';

    /**
     * @return string
     */
    public function GetName()
    {
        return 'staff';
    }

    /**
     * @param Request|null $request
     *
     * @return array
     */
    public function Valores(Request $request = null)
    {
        $staff = $this;

        return [
            new LibValoresCatalogo($this, __('metrics.name-staff'), 'name', [
                'notOrdenable' => false,
            ]),
            (new LibValoresCatalogo($this, __('metrics.usersNumber'), 'users_count', [
                'notOrdenable' => false,
            ])),
            (new LibValoresCatalogo($this, __('metrics.meetings'), 'meetings_count')),
            (new LibValoresCatalogo($this, __('metrics.ocupation-percent'), 'occupation'))->setGetValueNameFilter(function () use ($staff) {
                $occupation = round(($staff->occupation ?? 0) * 100, 2);

                return "{$occupation}%";
            })
        ];
    }

    /**
     * @return string
     */
    public function link(): string
    {
        return '';
    }

    /**
     * @param $query
     */
    static protected function filtrarQueries(&$query)
    {
        parent::filtrarQueries($query);
        $brands_id = LibFilters::getFilterValue('brands_id');
        $start = LibFilters::getFilterValue('start');
        $end = LibFilters::getFilterValue('end');
        $locations = LibFilters::getFilterValue('locations', null, []);

        $users_query = Reservation::selectRaw('count(distinct user_profiles_id) as users_count,count(*) as reservations_count,staff_id')->where(function ($q) use ($start, $end, $brands_id) {
            $q->where('brands_id', $brands_id);
            $q->whereDate('meeting_start', '>=', $start);
            $q->whereDate('meeting_start', '<=', $end);
            $q->where('cancelled', 0);
        })->when($locations, function ($q, $locations) {
            return $q->whereIn('locations_id', $locations);
        })->groupBy('staff_id');

        $users_query_sql = $users_query->toSql();
        foreach ($users_query->getBindings() as $binding) {
            $value = is_numeric($binding) ? $binding : "'" . $binding . "'";
            $users_query_sql = preg_replace('/\?/', $value, $users_query_sql, 1);
        }

        $meetings_query = Meeting::selectRaw('count(*) meetings_count,sum(capacity) total_capacity,staff_id')->where(function ($q) use ($start, $end, $brands_id) {
            $q->where('brands_id', $brands_id);
            $q->whereDate('start_date', '>=', $start);
            $q->whereDate('start_date', '<=', $end);
        })->when($locations, function ($q, $locations) {
            return $q->whereIn('locations_id', $locations);
        })->groupBy('staff_id');

        $meetings_query_sql = $meetings_query->toSql();
        foreach ($meetings_query->getBindings() as $binding) {
            $value = is_numeric($binding) ? $binding : "'" . $binding . "'";
            $meetings_query_sql = preg_replace('/\?/', $value, $meetings_query_sql, 1);
        }

        $query->selectRaw('staff.id,staff.name,ifnull(uc.users_count,0) users_count,if(total_capacity>0,ifnull(reservations_count,0)/total_capacity,0) occupation,ifnull(meetings_count,0) meetings_count')
            ->leftJoin(DB::raw('(' . $users_query_sql . ') uc'), 'uc.staff_id', 'staff.id')
            ->leftJoin(DB::raw('(' . $meetings_query_sql . ') mc'), 'mc.staff_id', 'staff.id');

        $query->whereHas('meetings', function ($q) use ($brands_id, $start, $end, $locations) {
            $q->where('brands_id', $brands_id);
            $q->whereDate('start_date', '>=', $start);
            $q->whereDate('start_date', '<=', $end);
            $q->when($locations, function ($q, $locations) {
                return $q->whereIn('locations_id', $locations);
            });
        });
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.brand.metrics.staff.filters');
    }

    public function GetSearchable()
    {
        return false;
    }

    public function GetAllowStatusSelector()
    {
        return false;
    }

}
