<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 06/08/2018
 * Time: 04:54 PM
 */

namespace App\Librerias\Catalog\Tables\Location\Metrics;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Credits\LibCredits;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Credit\CreditsBrand;
use App\Models\Credit\CreditsUserRelations;
use App\Models\User\UserProfile;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogUserCredits extends LibCatalogoModel
{
    use SoftDeletes, CreditsUserRelations;

    protected $table = 'users_credits';
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
        /**
         * @var UserProfile $profile
         */
        $profile = LibFilters::getFilterValue('profileFilter');
        $query->select([
            'credits_id',
            'credits_id as id',
            'expiration_date',
            'purchases_id',
            'brands_id',
            'companies_id',
            DB::raw('count(id) as total'),
        ])
            ->where(function ($q) use ($location, $profile) {
                $q->where('used', 0);
                $q->where('locations_id', $location->id);
                $q->where('expiration_date', '>', $location->now());
                $q->where('user_profiles_id', $profile->id);
            })
            ->orWhere(function ($q) use ($location, $profile) {
                $q->where('used', 0);
                $q->where('locations_id', '<>', $location->id);
                $q->where('expiration_date', '>', $location->now());
                $q->where('user_profiles_id', $profile->id);
                $q->whereHas('credit', function ($q) use ($location) {
                    $q->where('status', 'active');
                    $q->whereNull('locations_id');
                    $q->where('companies_id', $location->companies_id);
                });
            })
            ->with('credit')
            ->groupBy(['credits_id', 'expiration_date', 'purchases_id']);
    }

    public function Valores(Request $request = null)
    {
        $credit = $this;

        return [
            (new LibValoresCatalogo($this, __('credits.Name'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($credit) {
                return $credit->credit->name;

            }),
            (new LibValoresCatalogo($this, __('credits.brandsCredits'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($credit) {
                $credits_c = CreditsBrand::select('*')->where('credits_id', $credit->id)->get();
                $brands_c = LibCredits::getCreditsBrandsGF($credit->companies_id, $credits_c);
                $brands_com = [];
                foreach ($brands_c as $brand_c) {
                    array_push($brands_com, (string)$brand_c->name);
                }

                return $brands_com;
            }),
            (new LibValoresCatalogo($this, __('credits.total'), 'total', [
                'validator' => '',
            ])),
            (new LibValoresCatalogo($this, __('credits.expirationDate'), 'expiration_date', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($credit) {
                return $credit->expiration_date->format('Y-m-d h:i:s');
            }),
            (new LibValoresCatalogo($this, __('credits.Actions'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($credit) {
                return VistasGafaFit::view('admin.location.users.Creditos.buttons', ['credit' => $credit])->render();
            }),
            (new LibValoresCatalogo($this, '', 'purchases_id', [
                'validator'    => '',
                'hiddenInList' => true,
            ])),
            (new LibValoresCatalogo($this, __('subscriptions.Subscription'), ''))->setGetValueNameFilter(function () use ($credit) {
                $subscription = $credit->purchase->subscription ?? false;

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
            $query->whereHas('credit', function ($q) use ($criterio) {
                $q->where('name', 'like', "%{$criterio}%");
            });
        };
    }
}
