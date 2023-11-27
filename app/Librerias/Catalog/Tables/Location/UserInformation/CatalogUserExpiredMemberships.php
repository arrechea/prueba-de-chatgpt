<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 07/02/2019
 * Time: 16:28
 */

namespace App\Librerias\Catalog\Tables\Location\UserInformation;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Models\Credit\CreditsUserRelations;
use App\Models\User\UserMembershipTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class CatalogUserExpiredMemberships extends LibCatalogoModel
{
    use SoftDeletes, UserMembershipTrait;
    protected $table = 'users_memberships';
    protected $dates = [
        'expiration_date',
    ];

    public function GetName()
    {
        return 'UserCredits';
    }

    static protected function filtrarQueries(&$query)
    {
        $location = LibFilters::getFilterValue('locationFilter');
        $profile = LibFilters::getFilterValue('profileFilter');

        $wheres = function ($query) use ($profile, $location) {
            $query
                ->where([
                    ['user_profiles_id', $profile->id],
                ])->where(function ($query) use ($location) {
                    $query
                        ->where('locations_id', $location->id)
                        ->orWhere(function ($query) use ($location) {
                            $query
                                ->whereNull('locations_id')
                                ->where('brands_id', $location->brands_id);
                        });
                });
        };

        $query->where(function ($q) use ($wheres, $location) {
            $q->where($wheres);
            $q->where('expiration_date', '<', $location->now());
        })->orWhere(function ($q) use ($wheres) {
            $q->whereNotNull('deleted_at');
            $q->where($wheres);
        })->with('membership.credits')->withTrashed();
    }

    public function Valores(Request $request = null)
    {
        $user_membership = $this;

        return [
            (new LibValoresCatalogo($this, __('credits.Name'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($user_membership) {
                return $user_membership->membership->name;
            }),
            (new LibValoresCatalogo($this, __('credits.Credit'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($user_membership) {
                return $user_membership->membership->credits->first()->name;
            }),
            (new LibValoresCatalogo($this, __('credits.ReservationLimit'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($user_membership) {
                return $user_membership->membership->reservations_limit ?? __('credits.Unlimited');
            }),
            (new LibValoresCatalogo($this, __('credits.expirationDate'), 'expiration_date', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($user_membership) {
                return date_format($user_membership->expiration_date, 'd/m/Y');
            }),
            (new LibValoresCatalogo($this, __('credits.Canceled'), ''))->setGetValueNameFilter(function () use ($user_membership) {
                return $user_membership->deleted_at ? __('messages.yes') : __('messages.no');
            }),

        ];

    }

    public function link(): string
    {
        return '';
    }

    protected static function ColumnToSearch()
    {
        return function ($query, $criterio) {
            $query->whereHas('membership', function ($q) use ($criterio) {
                $q->where('name', 'like', "%{$criterio}%");
            });
        };
    }
}
