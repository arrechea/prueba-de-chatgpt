<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 08/08/2018
 * Time: 01:31 PM
 */

namespace App\Librerias\Catalog\Tables\Location\Metrics;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Purchase\PurchaseRelation;
use App\Models\User\UserProfile;
use function GuzzleHttp\Psr7\str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class CatalogUserPurchase extends LibCatalogoModel
{
    use SoftDeletes, PurchaseRelation;

    protected $table = 'purchases';

    public function GetName()
    {
        return 'Purchase';
    }

    static protected function filtrarQueries(&$query)
    {
        $location = LibFilters::getFilterValue('locationFilter');
        /**
         * @var UserProfile $profile
         */
        $profile = LibFilters::getFilterValue('profileFilter');

        $query->where('user_profiles_id', $profile->id)
            ->where('locations_id', $location->id);

        $query->orderBy('created_at', 'desc');

    }

    public function Valores(Request $request = null)
    {
        $purchase = $this;

        return [
            (new LibValoresCatalogo($this, __('purchases.Date'), 'created_at', [
                'validator' => 'nullable|in:pending,complete',
            ]))->setGetValueNameFilter(function () use ($purchase) {
                return date_format($purchase->created_at, 'd/m/Y');
            }),
            (new LibValoresCatalogo($this, 'GiftCard', '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($purchase) {
                if ($purchase->is_gift_card != true) {
                    return '--';
                } else {
                    return '<i class="material-icons">redeem</i>';
                }
            }),

            (new LibValoresCatalogo($this, __('purchases.DiscountCode'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($purchase) {
                if ($purchase->has_discount_code != true) {
                    return '--';
                } else {
                    return '<i class="material-icons">local_atm</i>';
                }
            }),

            new LibValoresCatalogo($this, __('purchases.Status'), 'status', [
                'validator' => 'nullable|in:pending,complete',
            ]),
            (new LibValoresCatalogo($this, 'Total', 'total', [
                'validator' => 'default:0',
            ]))->setGetValueNameFilter(function () use ($purchase) {
                return $purchase->printWithPrefix('total');
            }),
            (new LibValoresCatalogo($this, __('credits.Actions'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function ($lib, $value) use ($purchase) {
                return VistasGafaFit::view('admin.location.users.button', [
                    'purchase' => $purchase,
                ])->render();
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
            $query->where('id', 'like', "%{$criterio}%");
        };
    }

    public function GetAllowStatusSelector()
    {
        return false;
    }
}
