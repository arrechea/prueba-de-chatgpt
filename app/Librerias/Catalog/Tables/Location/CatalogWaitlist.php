<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 03/10/2018
 * Time: 09:24 AM
 */

namespace App\Librerias\Catalog\Tables\Location;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Models\Brand\Brand;
use App\Models\Location\Location;
use App\Models\Waitlist\WaitlistTrait;
use Illuminate\Http\Request;

class CatalogWaitlist extends LibCatalogoModel
{
    use WaitlistTrait;
    protected $table = 'waitlist';

    public function GetName()
    {
        return 'Waitlist';
    }

    public function link(): string
    {
        return '';
    }

    /**
     * @return bool
     */
    public function GetAllowStatusSelector()
    {
        return false;
    }

    /**
     * @return string
     */
    static protected function ColumnToSearch()
    {
        return 'id';
    }

    public function Valores(Request $request = null)
    {
        $waitlist = $this;

        return [
            (new LibValoresCatalogo(
                $this, __('reservations.Class'), '', [
                    'validator' => '',
                ]
            ))->setGetValueNameFilter(
                function () use ($waitlist) {
                    return strval($waitlist->meetings->service->name);
                }
            ),
            (new LibValoresCatalogo(
                $this, __('reservations.schedules'), '', [
                    'validator'    => '',
                    'notOrdenable' => false,
                ]
            ))->setGetValueNameFilter(
                function () use ($waitlist) {
                    return date_format($waitlist->meetings->start_date, 'd/m/Y g:i A');
                }
            ),
            (new LibValoresCatalogo(
                $this, __('reservations.Staff'), '', [
                    'validator' => '',
                ]
            ))->setGetValueNameFilter(
                function () use ($waitlist) {
                    return strval($waitlist->meetings->staff->name).' '.strval($waitlist->meetings->staff->last_name);
                }
            ),

            (new LibValoresCatalogo(
                $this, __('reservations.credits'), '', [
                    'validator' => '',
                ]
            ))->setGetValueNameFilter(
                function () use ($waitlist) {
                    return $waitlist->credit->name ?? '--';
                }
            ),

            (new LibValoresCatalogo(
                $this, __('reservations.membership'), '', [
                    'validator' => '',
                ]
            ))->setGetValueNameFilter(
                function () use ($waitlist) {
                    return $waitlist->membership->name ?? '--';
                }
            ),
        ];
    }

    protected static function filtrarQueries(&$query)
    {
        $userProfile = LibFilters::getFilterValue('profile');
        $locationId  = LibFilters::getFilterValue('locations_id');
        $brandsId    = LibFilters::getFilterValue('brands_id');
//        $reducePopulation = LibFilters::getFilterValue('reducePopulation');

        $query->where('user_profiles_id', $userProfile->id);
        $query->orderBy('meeting_start', 'desc');

        if ($locationId || $brandsId) {
            if ($locationId) {
                $location = Location::find($locationId);
                $query->where('locations_id', $locationId);

                $query->whereHas(
                    'meetings',
                    function ($query) use ($location) {
                        $query->where('meetings.start_date', '>', $location->now());
                    }
                );
            }

            if ($brandsId) {
                $brand = Brand::find($brandsId);
                $query->where('brands_id', $brandsId);
                $query->whereHas(
                    'meetings',
                    function ($query) use ($brand) {
                        $query->where('meetings.start_date', '>', $brand->now());
                    }
                );
            }
        } else {
            $query->where('meeting_start', '>', Carbon::now());
        }
//
//        $query->with(['meetings', 'credit', 'user', 'location', 'meetings.service']);
//        if ($reducePopulation) {
//            $query->with(
//                [
//                    'staff'           => function ($query) {
//                        $query->select(['id', 'name']);
//                    },
//                    'service'         => function ($query) {
//                        $query->select(['id', 'name']);
//                    },
//                    'user',
//                    'location'        => function ($query) {
//                        $query->select(['id', 'slug', 'name']);
//                    },
//                    'room'            => function ($query) {
//                        $query->select(['id', 'name']);
//                    },
//                    'user_membership' => function ($query) {
//                        $query
//                            ->select(['id', 'memberships_id', 'expiration_date', 'reservations_limit'])
//                            ->with(
//                                [
//                                    'membership' => function ($query) {
//                                        $query->select(['id', 'name', 'pic']);
//                                    },
//                                ]
//                            );
//                    },
//                ]
//            );
//        }
    }

//    public function GetHtmlExtraEnHeaderIndex()
//    {
//        return VistasGafaFit::view('admin.location.waitlist.filters');
//    }

    public function GetOtherFilters()
    {
        return 'waitlist-filter';
    }
}
