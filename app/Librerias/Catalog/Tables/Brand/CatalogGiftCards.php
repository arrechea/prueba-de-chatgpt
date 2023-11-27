<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 20/09/2018
 * Time: 10:06 AM
 */

namespace App\Librerias\Catalog\Tables\Brand;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Purchase\PurchaseGiftCardTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CatalogGiftCards extends LibCatalogoModel
{
    use PurchaseGiftCardTrait;
    protected $table = 'purchase_gift_cards';

    public function GetName()
    {
        return 'PurchaseGiftCards';
    }

    public function link(): string
    {
        return '';
    }

    static protected function filtrarQueries(&$query)
    {
        parent::filtrarQueries($query);

        $brands_id = LibFilters::getFilterValue('brands_id');

        $query
            ->where('brands_id', $brands_id)
            ->with(['user_profile', 'admin_profile','purchase' ]);
    }

    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.brand.marketing.info');
    }

    static protected function ColumnToSearch()
    {
        $brands_id = LibFilters::getFilterValue('brands_id');

        return function ($query, $criterio) use ($brands_id) {
            $query->where('code', 'like', "%{$criterio}%");
            $query->where('brands_id', $brands_id);
        };
    }

    public function Valores(Request $request = null)
    {
        $giftCard = $this;
        $brand = $giftCard->brand;
        $authUser = Auth::user();

        return [

//            (new LibValoresCatalogo($this, __('giftcards.code'), 'code', [
//                'validator' => '',
//            ])),
            (new LibValoresCatalogo($this, __('giftcards.redeem'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function ($lib, $value) use ($giftCard) {
                return VistasGafaFit::view('admin.common.check', [
                    'active' => $giftCard->isRedeemed(),
                ])->render();
            }),
            (new LibValoresCatalogo($this, __('giftcards.redeemByUser'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($giftCard) {
                if ($giftCard->redempted_by_user_profiles_id != null) {
                   $profile = $giftCard->user_profile;
                    $user = $profile->id.' '.$profile->first_name.' '.$profile->last_name;
                    return "#$user";
                } else {
                    return '--';
                }
            }),
            (new LibValoresCatalogo($this, __('giftcards.redeemByAdmin'),'',[
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($giftCard) {
                if ($giftCard->redempted_by_admin_profiles_id != null) {
                    $profile = $giftCard->admin_profile;
                    $admin = $profile->id.' '.$profile->first_name.' '.$profile->last_name;
                    return "#$admin";
                } else {
                    return '--';
                }
            }),
            (new LibValoresCatalogo($this, __('giftcards.redeemTo'),'',[
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($giftCard){
               if ($giftCard->isRedeemed() != 0){
                $redeem_at = date_create($giftCard->redempted_at);
                   return date_format($redeem_at, 'd/m/Y');
               }
               else{
                   return '--';
               }
            }),
            (new LibValoresCatalogo($this,__('giftcards.purchase'),'',[
                'validator' => '',
            ]))->setGetValueNameFilter(function() use ($giftCard){

                return VistasGafaFit::view('admin.brand.catalogs.purchases.button',[
                    'purchase' => $giftCard->purchase,
                ])->render();
            }),
        ];
    }
}
