<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 07/08/2018
 * Time: 04:42 PM
 */

namespace App\Librerias\Catalog\Tables\Location\Metrics;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Models\Credit\CreditsUserRelations;
use App\Models\User\UserProfile;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogUserCreditsPast extends LibCatalogoModel
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

        $query->where('user_profiles_id', $profile->id)
            ->select([
                'credits_id',
                'credits_id as id',
                'expiration_date',
                DB::raw('count(id) as total'),
            ])
            ->with('credit')
            ->where('used', 0)
            ->where('locations_id', $location->id)
            ->where('expiration_date', '<=', $location->now())
            ->groupBy(['credits_id', 'expiration_date']);
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
            (new LibValoresCatalogo($this, __('credits.total'), 'total', [
                'validator' => '',
            ])),
            (new LibValoresCatalogo($this, __('credits.expirationDate'), 'expiration_date', [
                'validator' => '',
            ])),
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
