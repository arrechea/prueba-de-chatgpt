<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 18/09/2018
 * Time: 05:46 PM
 */

namespace App\Http\Controllers\Api\GiftCards;


use App\Http\Controllers\ApiController;
use App\Http\Requests\ApiRequest as Request;
use App\Librerias\GiftCards\LibGiftCards;
use App\Models\Brand\Brand;
use App\Models\Purchase\PurchaseGiftCard;
use Illuminate\Support\Facades\Auth;

class GiftCardsApiController extends ApiController
{
    public function redeem(Request $request, Brand $brand)
    {
        if (!$request->has('code') || ($code = $request->get('code')) === '') {
            abort(403, __('giftcards.ErrorInputCode'));
        }

        $user = Auth::user()->getProfileInThisCompany();

        $response = LibGiftCards::saveGiftCard($user, $code, $brand);

        return $response instanceof PurchaseGiftCard ? $response : abort(403, $response);
    }
}
