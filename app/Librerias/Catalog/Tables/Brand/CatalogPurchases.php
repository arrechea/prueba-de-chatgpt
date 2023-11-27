<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 08/10/2018
 * Time: 09:47 AM
 */

namespace App\Librerias\Catalog\Tables\Brand;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Purchase\PurchaseRelation;
use App\Models\User\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CatalogPurchases extends LibCatalogoModel
{
    use PurchaseRelation;
    protected $table = 'purchases';

    public function GetName()
    {
        return 'Purchase';
    }

    public function link(): string
    {
        return '';
    }

    static protected function filtrarQueries(&$query)
    {
        parent::filtrarQueries($query);
        $brands_id = LibFilters::getFilterValue('brands_id');

        $query->where('brands_id', $brands_id)
            ->with(['user_profile']);

    }

    static protected function ColumnToSearch()
    {
        $brands_id = LibFilters::getFilterValue('brands_id');

        return function ($query, $criterio) use ($brands_id) {
            $query->where('id', 'like', "%{$criterio}%")
                ->where('brands_id', $brands_id);
            $query->orWhere(function ($q) use ($criterio) {
                $q->whereHas('user_profile', function ($q) use ($criterio) {
                    $q->where('first_name', 'like', "%{$criterio}%");
                    $q->orwhere('last_name', 'like', "%{$criterio}%");
                });
            });

        };
    }

    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.brand.marketing.info');
    }

    public function GetAllowStatusSelector()
    {
        return false;
    }

    public function Valores(Request $request = null)
    {
        $purchase = $this;

        return [
            (new LibValoresCatalogo($this, __('purchases.Date'), 'created_at', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($purchase) {
                return date_format($purchase->created_at, 'd/m/Y');
            }),

            (new LibValoresCatalogo($this, __('purchases.user-name'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($purchase) {
                return $purchase->user_profile->first_name . ' ' . $purchase->user_profile->last_name;
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
            (new LibValoresCatalogo($this, __('giftcards.purchase'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($purchase) {

                return
                    VistasGafaFit::view('admin.brand.catalogs.purchases.button', [
                        'purchase' => $purchase,
                    ])->render();


            }),
        ];
    }

}
