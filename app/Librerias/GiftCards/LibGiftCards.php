<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 18/09/2018
 * Time: 12:01 PM
 */

namespace App\Librerias\GiftCards;


use App\Models\Admin\AdminProfile;
use App\Models\Brand\Brand;
use App\Models\Purchase\PurchaseGiftCard;
use App\Models\User\UserProfile;
use Illuminate\Support\Facades\Auth;

class LibGiftCards
{
    /**
     * Busca el código de regalo dentro de una cierta marca. Si se le manda
     * la variable $get_model con valor true, regresa el modelo PurchaseGiftCard
     * asociado a el código y marca.
     *
     * @param string $code
     * @param int    $brand_id
     * @param bool   $get_model
     *
     * @return bool
     */
    public static function checkCode(string $code, int $brand_id, bool $get_model = false)
    {
        $gift_card = PurchaseGiftCard::where([
            ['code', $code],
            ['brands_id', $brand_id],
            ['is_active', 1],
        ])->first();

        if ($get_model)
            return $gift_card;
        else
            return $gift_card ? true : false;
    }

    /**
     *
     *
     * @param UserProfile $profile
     * @param string      $code
     * @param Brand       $brand
     * @param             $auth
     *
     * @return mixed
     */
    public static function saveGiftCard(UserProfile $profile, string $code, Brand $brand, AdminProfile $auth = null)
    {
        $gift_card = self::checkCode($code, $brand->id, true);
        if ($gift_card && $gift_card->brands_id === $brand->id) {
            return $gift_card->assignToUser($profile, $auth);
        }

        return __('giftcards.ErrorInvalidCode');
    }
}
