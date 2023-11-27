<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 05/06/2018
 * Time: 11:02 AM
 */

namespace App\Librerias\Catalog\Tables\Location\Reservations;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Models\Brand\Brand;
use App\Models\Location\Location;
use App\Models\Reservation\ReservationsRelationship;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class CatalogReservations extends LibCatalogoModel
{
    use ReservationsRelationship, SoftDeletes;
    protected $table = 'reservations';
    protected $casts = [
        'cancelled' => 'boolean',
    ];

    protected $dates = [
        'cancelation_dead_line',
    ];

    public function Valores(Request $request = null)
    {
        $reservation = $this;
        $active = $this->isCancelled();


        return [
            (new LibValoresCatalogo($this, __('reservations.Class'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($reservation) {
                return strval($reservation->meetings->service->name);
            }),
            (new LibValoresCatalogo($this, __('reservations.schedules'), '', [
                'validator'    => '',
                'notOrdenable' => false,
            ]))->setGetValueNameFilter(function () use ($reservation) {
                return date_format($reservation->meetings->start_date, 'd/m/Y g:i A');
            }),
            (new LibValoresCatalogo($this, __('reservations.Staff'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($reservation) {
                return strval($reservation->meetings->staff->name) . ' ' . strval($reservation->meetings->staff->last_name);
            }),

            (new LibValoresCatalogo($this, __('reservations.credits'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($reservation) {
                return $reservation->credit->name ?? '--';
            }),

            (new LibValoresCatalogo($this, __('reservations.membership'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($reservation) {
                return $reservation->membership->name ?? '--';
            }),

            (new LibValoresCatalogo($this, __('reservations.Cancelled'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function ($lib, $value) use ($active) {
                return $active ?
                    '<svg height="20" width="50">
                <circle cx="25" cy="10" r="7" stroke="black" stroke-width="2" fill="red" />
                </svg>' :
                    '';
            }),

//            (new LibValoresCatalogo($this, __('reservations.Actions'), '', [
//                'validator' => '',
//            ]))->setGetValueNameFilter(function ($lib) use ($reservation, $meeting) {
//                return VistasGafaFit::view('admin.location.reservations.users.boton', [
//                    'reservation' => $reservation,
//                    'meeting'     => $meeting,
//                ])->render();
//            }),
        ];
    }

    /**
     * @param $query
     */
    static protected function filtrarQueries(&$query)
    {

        $userProfile = LibFilters::getFilterValue('profile');
        $locationId = LibFilters::getFilterValue('locations_id');
        $brandsId = LibFilters::getFilterValue('brands_id');
        $reducePopulation = LibFilters::getFilterValue('reducePopulation');

        $query->where('user_profiles_id', $userProfile->id);
        $query->orderBy('meeting_start','desc');

        if ($locationId || $brandsId) {
            if ($locationId) {
                $location = Location::find($locationId);
                $query->where('locations_id', $locationId);

                $query->whereHas('meetings', function ($query) use ($location) {
                    $query->where('meetings.start_date', '<', $location->now());
                });
            }

            if ($brandsId) {
                $brand = Brand::find($brandsId);
                $query->where('brands_id', $brandsId);
                $query->whereHas('meetings', function ($query) use ($brand) {
                    $query->where('meetings.start_date', '<', $brand->now());
                });
            }
        } else {
            $query->where('meeting_start', '<', Carbon::now());
        }

        $query->with([
            'meetings',
            'credit',
            'user',
            'location',
            'meetings.service',
        ]);
        if ($reducePopulation) {
            $query->with([
                'staff'    => function ($query) {
                    $query->select([
                        'id',
                        'name',
                    ]);
                },
                'service'  => function ($query) {
                    $query->select([
                        'id',
                        'name',
                    ]);
                },
                'user',
                'location' => function ($query) {
                    $query->select([
                        'id',
                        'slug',
                        'name',
                    ]);
                },
                'room' => function ($query) {
                    $query->select([
                        'id',
                        'name',
                    ]);
                },
                'user_membership' => function ($query) {
                    $query->select([
                        'id',
                        'memberships_id',
                        'expiration_date',
                        'reservations_limit',
                    ])->with([
                        'membership' => function ($query) {
                            $query->select([
                                'id',
                                'name',
                                'pic',
                            ]);
                        },
                    ]);
                },
            ]);
        }
    }

    /**
     * @return string
     */
    public function GetName()
    {
        return 'Reservations';
    }

    /**
     * Link del modelo
     *
     * @return string
     */
    public function link(): string
    {
        return '';
    }

    protected static function ColumnToSearch()
    {
        return function ($q, $c) {
            $q->whereHas('service', function ($q) use ($c) {
                $q->where('name', 'like', "%{$c}%");
            });
        };
    }
}
