<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 07/02/2019
 * Time: 12:21
 */

namespace App\Librerias\Catalog\Tables\Location\UserInformation;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Credits\LibCredits;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Credit\CreditsBrand;
use App\Models\Credit\CreditsUserRelations;
use App\Models\User\UserMembershipTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class CatalogUserActiveMemberships extends LibCatalogoModel
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

        $query
            ->where([
                ['user_profiles_id', $profile->id],
//                ['locations_id', $location->id],
                ['expiration_date', '>', $location->now()],
            ])->where(function ($query) use ($location) {
                $query
                    ->where('locations_id', $location->id)
                    ->orWhere(function ($query) use ($location) {
                        $query
                            ->whereNull('locations_id')
                            ->where('brands_id', $location->brands_id);
                    });
            })
            ->with('membership.credits');
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
            (new LibValoresCatalogo($this, __('credits.brandsCredits'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($user_membership) {
                $credits_c = CreditsBrand::select('*')->where('credits_id', $user_membership->membership->credits->first()->id)->get();
                $brands_c = LibCredits::getCreditsBrandsGF($user_membership->companies_id, $credits_c);
                $brands_com = [];
                foreach ($brands_c as $brand_c) {
                    array_push($brands_com, (string)$brand_c->name);
                }

                return $brands_com;
            }),
            (new LibValoresCatalogo($this, __('credits.ReservationLimit'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($user_membership) {
                return $user_membership->membership->reservations_limit ?? __('credits.Unlimited');
            }),
            (new LibValoresCatalogo($this, __('credits.expirationDate'), 'expiration_date', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($user_membership) {
                return $user_membership->expiration_date->format('Y-m-d h:i:s');
            }),
            (new LibValoresCatalogo($this, __('credits.Actions'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($user_membership) {
                return VistasGafaFit::view('admin.location.users.memberships.button', [
                    'id'              => $this->getQueueableId(),
                    'expiration_date' => $user_membership->expiration_date,
                ])->render();
            }),
            (new LibValoresCatalogo($this, __('subscriptions.Subscription'), ''))->setGetValueNameFilter(function () use ($user_membership) {
                $subscription = $user_membership->purchase->subscription ?? false;

                return $subscription ? "#$subscription" : '';
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
