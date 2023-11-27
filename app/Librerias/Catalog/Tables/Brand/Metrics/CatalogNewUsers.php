<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 24/07/2018
 * Time: 03:17 PM
 */

namespace App\Librerias\Catalog\Tables\Brand\Metrics;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Helpers\LibMath;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Reservation\Reservation;
use App\Models\User\ProfileTrait;
use App\Models\User\UserProfile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogNewUsers extends LibCatalogoModel
{
    use ProfileTrait;
    protected $table = 'user_profiles';
    protected $casts = [
        'verified' => 'boolean',
    ];

    public function GetName()
    {
        return 'New Users';
    }

    public function Valores(Request $request = null)
    {
        $user = $this;

        $name = new LibValoresCatalogo($this, __('metrics.user'), 'full_name', [
            'validator'    => '',
            'notOrdenable' => false,
        ]);

        $gender = new LibValoresCatalogo($this, __('metrics.sex'), 'gender');
        $gender->setGetValueNameFilter(function () use ($user) {
            return $user->gender ? __('gender.' . $user->gender) : '--';
        });

        $staff = new LibValoresCatalogo($this, __('metrics.staff'), 'staff_name');

        $back = new LibValoresCatalogo($this, __('metrics.back'), 'reservations_count');
        $back->setGetValueNameFilter(function () use ($user) {
            return $user->reservations_count > 1 ? __('messages.yes') : __('messages.no');
        });

        $age = new LibValoresCatalogo($this, __('metrics.age'), 'age');
        $age->setGetValueNameFilter(function () use ($user) {
            return $user->age ?? 0;
        });

        return [
            $name,
            $gender,
            $staff,
            $back,
            $age,
        ];
    }

    public function link(): string
    {
        return '';
    }

    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.brand.metrics.users.filters.new-users');
    }

    protected static function filtrarQueries(&$query)
    {
        parent::filtrarQueries($query);
        $brands_id = LibFilters::getFilterValue('brands_id');
        $start = LibFilters::getFilterValue('start', null, Carbon::now()->subWeek());
        $end = LibFilters::getFilterValue('end', null, Carbon::now());
        $not_in = LibFilters::getFilterValue('not-in', null, false);
        $not_in_start = LibFilters::getFilterValue('not-in-start', null, Carbon::now()->subWeek());
        $not_in_end = LibFilters::getFilterValue('not-in-end', null, Carbon::now());
        $locations = LibFilters::getFilterValue('locations', null, []);


        $earliest_reservations_sql = self::getEarliestReservationsSql($brands_id, $locations);

        $query->where(function ($q) use ($start, $end, $not_in, $not_in_end, $not_in_start) {
            $q->whereDate('meeting_start', '>=', $start);
            $q->whereDate('meeting_start', '<=', $end);
            if ($not_in) {
                $q->where(function ($q) use ($not_in_start, $not_in_end) {
                    $q->whereDate('meeting_start', '<', $not_in_start);
                    $q->orWhere(function ($q) use ($not_in_end) {
                        $q->whereDate('meeting_start', '>', $not_in_end);
                    });
                });
            }
        });

        $query->join(DB::raw('(' . $earliest_reservations_sql . ') as res'), 'res.user_profiles_id', 'user_profiles.id');

        $query->select('user_profiles.*', DB::raw('staff_name,
        timestampdiff(year,birth_date,curdate()) age,
        concat(first_name," ",last_name) full_name'));

        $query->withCount(['reservations' => function ($q) use ($brands_id, $locations) {
            $q->where('brands_id', $brands_id);
            $q->where('cancelled', 0);
            $q->when($locations, function ($q, $locations) {
                return $q->whereIn('locations_id', $locations);
            });
        }]);
        $query->whereHas('reservations', function ($q) use ($brands_id, $locations) {
            $q->where('brands_id', $brands_id);
            $q->where('cancelled', 0);
            $q->when($locations, function ($q, $locations) {
                return $q->whereIn('locations_id', $locations);
            });
        });

        $query->where('status', 'active');
    }

    protected static function ColumnToSearch()
    {
        return function ($query, $criterio) {
            $query->where(function ($q) use ($criterio) {
                $q->where('first_name', 'like', "%{$criterio}%");
                $q->orWhere('last_name', 'like', "%{$criterio}%");
            });
        };
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function GetFooterHtml()
    {
        $brands_id = LibFilters::getFilterValue('brands_id');
        $start = LibFilters::getFilterValue('start', null, Carbon::now()->subWeek());
        $end = LibFilters::getFilterValue('end', null, Carbon::now());
        $not_in = LibFilters::getFilterValue('not-in', null, false);
        $not_in_start = LibFilters::getFilterValue('not-in-start', null, Carbon::now()->subWeek());
        $not_in_end = LibFilters::getFilterValue('not-in-end', null, Carbon::now());
        $locations = LibFilters::getFilterValue('locations', null, []);

        $earliest_reservations_sql = self::getEarliestReservationsSql($brands_id, $locations);

        $query = UserProfile::select('id',
            DB::raw('timestampdiff(year,date(birth_date),curdate()) age,id'))
            ->where(function ($q) use ($start, $end, $not_in, $not_in_end, $not_in_start) {
                $q->whereDate('meeting_start', '>=', $start);
                $q->whereDate('meeting_start', '<=', $end);
                if ($not_in) {
                    $q->where(function ($q) use ($not_in_start, $not_in_end) {
                        $q->whereDate('meeting_start', '<', $not_in_start);
                        $q->orWhere(function ($q) use ($not_in_end) {
                            $q->whereDate('meeting_start', '>', $not_in_end);
                        });
                    });
                }

            })
            ->withCount(['reservations' => function ($q) use ($brands_id, $locations) {
                $q->where('brands_id', $brands_id);
                $q->where('cancelled', 0);
                $q->when($locations, function ($q, $locations) {
                    return $q->whereIn('locations_id', $locations);
                });
            }])
            ->join(DB::raw('(' . $earliest_reservations_sql . ') as res'), 'res.user_profiles_id', 'user_profiles.id');

        $query->whereHas('reservations', function ($q) use ($brands_id, $locations) {
            $q->where('brands_id', $brands_id);
            $q->where('cancelled', 0);
            $q->when($locations, function ($q, $locations) {
                return $q->whereIn('locations_id', $locations);
            });
        });

        $users = $query->get();

        $back_percentage = LibMath::percentage($users->where('reservations_count', '>', 1)->count(), $users->where('reservations_count', '>', 0)->count());
        $average_age = $users->avg('age');

        return VistasGafaFit::view('admin.brand.metrics.users.footer', [
            'back_percentage' => $back_percentage,
            'age_average'     => $average_age,
        ])->render();
    }

    public function GetAllowStatusSelector()
    {
        return false;
    }

    private static function getEarliestReservationsSql($brands_id, $locations = [])
    {
        $earliest_dates = DB::table('reservations as res1')->selectRaw('min(res1.meeting_start) min_start,res1.user_profiles_id profile_id')->where([
            ['cancelled', 0],
            ['brands_id', $brands_id],
        ])->when($locations, function ($q, $locations) {
            return $q->whereIn('locations_id', $locations);
        })->groupBy('res1.user_profiles_id');

        $earliest_dates_sql = $earliest_dates->toSql();
        foreach ($earliest_dates->getBindings() as $binding) {
            $value = is_numeric($binding) ? $binding : "'" . $binding . "'";
            $earliest_dates_sql = preg_replace('/\?/', $value, $earliest_dates_sql, 1);
        }

        $reservations = DB::table('reservations as res2')->join(DB::raw('(' . $earliest_dates_sql . ') ed'), function ($q) {
            $q->on('ed.min_start', '=', 'res2.meeting_start');
            $q->on('ed.profile_id', '=', 'res2.user_profiles_id');
        })->where([
            ['cancelled', 0],
            ['brands_id', $brands_id],
        ])->when($locations, function ($q, $locations) {
            return $q->whereIn('locations_id', $locations);
        });

        $reservations_sql = $reservations->toSql();
        foreach ($reservations->getBindings() as $binding) {
            $value = is_numeric($binding) ? $binding : "'" . $binding . "'";
            $reservations_sql = preg_replace('/\?/', $value, $reservations_sql, 1);
        }

        $reservations_group = DB::table(DB::raw('(' . $reservations_sql . ') res3'))
            ->selectRaw('min(res3.id)')
            ->groupBy('res3.meeting_start', 'res3.user_profiles_id');

        $earliest_reservations = Reservation::selectRaw('reservations.user_profiles_id,concat(staff.name," ",staff.lastname) staff_name,reservations.meeting_start')
            ->whereRaw('reservations.id in (' . $reservations_group->toSql() . ')')
            ->leftJoin('staff', 'staff.id', 'reservations.staff_id');

        $earliest_reservations_sql = $earliest_reservations->toSql();
        foreach ($earliest_reservations->getBindings() as $binding) {
            $value = is_numeric($binding) ? $binding : "'" . $binding . "'";
            $earliest_reservations_sql = preg_replace('/\?/', $value, $earliest_reservations_sql, 1);
        }

        return $earliest_reservations_sql;
    }
}
