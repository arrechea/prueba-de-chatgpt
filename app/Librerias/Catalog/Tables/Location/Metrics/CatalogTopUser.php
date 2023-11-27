<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 25/07/2018
 * Time: 04:12 PM
 */

namespace App\Librerias\Catalog\Tables\Location\Metrics;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Reservation\Reservation;
use App\Models\User\ProfileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogTopUser extends LibCatalogoModel
{
    use ProfileTrait;
    protected $table = 'user_profiles';
    protected $casts = [
        'verified' => 'boolean',
    ];

    public function GetName()
    {
        return 'Top Users';
    }

    public function Valores(Request $request = null)
    {
        $user = $this;

        return [
            new LibValoresCatalogo($this, __('metrics.user'), 'name'),
            new LibValoresCatalogo($this, __('metrics.reservations'), 'reservations_count'),
            new LibValoresCatalogo($this, __('metrics.email'), 'email'),
            new LibValoresCatalogo($this, __('metrics.top-staff'), 'staff_names'),
        ];
    }

    public function link(): string
    {
        return '';
    }

    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.location.metrics.users.filters.top-users');
    }

    protected static function filtrarQueries(&$query)
    {
        parent::filtrarQueries($query);
        $locations_id = LibFilters::getFilterValue('locations_id');
        $start = LibFilters::getFilterValue('start');
        $end = LibFilters::getFilterValue('end');
        $not_in = LibFilters::getFilterValue('not-in');
        $not_in_start = LibFilters::getFilterValue('not-in-start');
        $not_in_end = LibFilters::getFilterValue('not-in-end');

        $res_count = Reservation::select(DB::raw('count(*) staff_count,staff_id,user_profiles_id,staff.name staff_name'))
            ->where('locations_id', $locations_id)
            ->where('cancelled', 0)
            ->whereDate('reservations.created_at', '>=', $start)
            ->whereDate('reservations.created_at', '<=', $end)
            ->where(function ($q) use ($not_in_end, $not_in_start, $not_in) {
                if (!!$not_in && !!$not_in_end && !!$not_in_start) {
                    $q->whereDate('reservations.created_at', '>', $not_in_end);
                    $q->orWhere(function ($q) use ($not_in_start) {
                        $q->whereDate('reservations.created_at', '<', $not_in_start);
                    });
                }
            })
            ->leftJoin('staff', 'staff.id', '=', 'reservations.staff_id')
            ->groupBy('staff_id', 'user_profiles_id');

        $res_count_sql = $res_count->toSql();
        foreach ($res_count->getBindings() as $binding) {
            $value = is_numeric($binding) ? $binding : "'" . $binding . "'";
            $res_count_sql = preg_replace('/\?/', $value, $res_count_sql, 1);
        }

        $max_count = DB::table(DB::raw('(' . $res_count_sql . ') as c'))
            ->select(DB::raw('max(staff_count) max_count,user_profiles_id profile_id'))
            ->groupBy('profile_id');

        $max_staff = DB::table(DB::raw('(' . $res_count_sql . ') m'))->join(DB::raw('(' . $max_count->toSql() . ') as mc'), function ($q) {
            $q->on('mc.max_count', 'm.staff_count');
            $q->on('mc.profile_id', 'm.user_profiles_id');
        })->select('user_profiles_id', 'staff_name');

        $concat = DB::table(DB::raw('(' . $max_staff->toSql() . ') as ms'))
            ->selectRaw('group_concat(staff_name separator ", ") as staff_names,user_profiles_id')
            ->groupBy('user_profiles_id');

        $query->leftJoin(DB::raw('(' . $concat->toSql() . ') as ts'), 'ts.user_profiles_id', '=', 'user_profiles.id');

        $query->select('id', 'ts.staff_names', 'email', DB::raw('concat(first_name," ",last_name) name'));

        $query->withCount(['reservations' => function ($q) use ($locations_id, $start, $end, $not_in, $not_in_start, $not_in_end) {
            $q->where('locations_id', $locations_id);
            $q->where('cancelled', 0);

            if (!!$start && !!$end) {
                $q->whereDate('created_at', '>=', $start);
                $q->whereDate('created_at', '<=', $end);
            }

            if ($not_in) {
                $q->where(function ($q) use ($not_in_end, $not_in_start) {
                    $q->whereDate('created_at', '>', $not_in_end);
                    $q->orWhere(function ($q) use ($not_in_start) {
                        $q->whereDate('created_at', '<', $not_in_start);
                    });
                });
            }
        }]);

        $query->whereHas('reservations', function ($q) use ($locations_id, $start, $end, $not_in, $not_in_start, $not_in_end) {
            $q->where('locations_id', $locations_id);
            $q->where('cancelled', 0);

            if (!!$start && !!$end) {
                $q->whereDate('created_at', '>=', $start);
                $q->whereDate('created_at', '<=', $end);
            }

            if (!!$not_in && !!$not_in_end && !!$not_in_start) {
                $q->where(function ($q) use ($not_in_end, $not_in_start) {
                    $q->whereDate('created_at', '>', $not_in_end);
                    $q->orWhere(function ($q) use ($not_in_start) {
                        $q->whereDate('created_at', '<', $not_in_start);
                    });
                });
            }
        });

        $query->where('status', 'active');
    }

    protected static function ColumnToSearch()
    {
        return function ($query, $criterio) {
            $query->where(function ($q) use ($criterio) {
                $q->where('first_name', 'like', "%{$criterio}%");
                $q->orWhere('last_name', 'like', "%{$criterio}%");
                $q->orWhere('email', 'like', "%{$criterio}%");
//                $q->orWhere('staff_names', 'like', "%{$criterio}%");
            });
        };
    }

    public function GetAllowStatusSelector()
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
}
