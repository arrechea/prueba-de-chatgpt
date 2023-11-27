<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 24/07/2018
 * Time: 03:18 PM
 */

namespace App\Librerias\Catalog\Tables\Location\Metrics;


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
//            (new LibValoresCatalogo($this, __('metrics.ocupation-percent'), '', [
//                'notOrdenable' => true,
//            ]))->setGetValueNameFilter(function () use ($staff) {
//                $reservas = $staff->reservations->count() > 0 ? $staff->reservations->first()->reservations_count : 0;
//                $capacidad = $staff->meetings->count() > 0 ? $staff->meetings->first()->suma : 0;
//
//                $ocupacionPromedio = $capacidad === 0 ? 0 : (($reservas / $capacidad) * 100);
//
//                return round($ocupacionPromedio) . '%';
//            }),
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
        $locations_id = LibFilters::getFilterValue('locations_id');
        $start = LibFilters::getFilterValue('start');
        $end = LibFilters::getFilterValue('end');

        $users_query = Reservation::selectRaw('count(distinct user_profiles_id) as users_count,count(*) as reservations_count,staff_id')->where(function ($q) use ($start, $end, $locations_id) {
            $q->where('locations_id', $locations_id);
            $q->whereDate('meeting_start', '>=', $start);
            $q->whereDate('meeting_start', '<=', $end);
            $q->where('cancelled',0);
        })->groupBy('staff_id');

        $users_query_sql = $users_query->toSql();
        foreach ($users_query->getBindings() as $binding) {
            $value = is_numeric($binding) ? $binding : "'" . $binding . "'";
            $users_query_sql = preg_replace('/\?/', $value, $users_query_sql, 1);
        }

        $meetings_query = Meeting::selectRaw('count(*) meetings_count,sum(capacity) total_capacity,staff_id')->where(function ($q) use ($start, $end, $locations_id) {
            $q->where('locations_id', $locations_id);
            $q->whereDate('start_date', '>=', $start);
            $q->whereDate('start_date', '<=', $end);
        })->groupBy('staff_id');

        $meetings_query_sql = $meetings_query->toSql();
        foreach ($meetings_query->getBindings() as $binding) {
            $value = is_numeric($binding) ? $binding : "'" . $binding . "'";
            $meetings_query_sql = preg_replace('/\?/', $value, $meetings_query_sql, 1);
        }

        $query->selectRaw('staff.id,staff.name,ifnull(uc.users_count,0) users_count,if(total_capacity>0,ifnull(reservations_count,0)/total_capacity,0) occupation,ifnull(meetings_count,0) meetings_count')
            ->leftJoin(DB::raw('(' . $users_query_sql . ') uc'), 'uc.staff_id', 'staff.id')
            ->leftJoin(DB::raw('(' . $meetings_query_sql . ') mc'), 'mc.staff_id', 'staff.id');

        $query->whereHas('meetings', function ($q) use ($locations_id, $start, $end) {
            $q->where('locations_id', $locations_id);
            $q->whereDate('start_date', '>=', $start);
            $q->whereDate('start_date', '<=', $end);
        });

//        $query
////            ->with(['meetings' => function ($q) use ($locations_id) {
////                $q->select(DB::raw('avg(capacity)as meetings_capacity, sum(capacity)as suma, count(*) as meetings_count,  staff_id'))->groupBy('staff_id');
////                $q->where('locations_id', $locations_id);
////            }])
////            ->with([
////                'reservations' => function ($q) use ($locations_id, $start, $end) {
////                    $q->select(DB::raw('count(distinct user_profiles_id) user_count, count(id) as reservations_count ,staff_id'))->groupBy('staff_id');
////                    $q->where('locations_id', (int)$locations_id);
////                    $q->whereDate('meeting_start', '>=', $start);
////                    $q->whereDate('meeting_start', '<=', $end);
////                },
////            ])
//            ->withCount([
//                'meetings' => function ($q) use ($locations_id, $start, $end) {
//                    $q->where('locations_id', (int)$locations_id);
//                    $q->whereDate('start_date', '>=', $start);
//                    $q->whereDate('start_date', '<=', $end);
//                },
//            ]);

//        dd($query->get());
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.location.metrics.staff.filters');
    }

    public function GetSearchable()
    {
        return false;
    }

    public function GetAllowStatusSelector()
    {
        return false;
    }

    /**
     * @return string
     */
//    public function GetOtherFilters()
//    {
//        return 'filtros--ventas';
//    }

}
