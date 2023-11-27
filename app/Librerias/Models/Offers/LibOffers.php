<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 30/04/2018
 * Time: 05:42 PM
 */

namespace App\Librerias\Models\Offers;

use App\Http\Requests\AdminRequest as Request;
use App\Models\Brand\Brand;
use App\Models\Offer\Offer;

abstract class LibOffers
{
    public static function nullMarketingFields(Request $request)
    {
        $type = $request->get('type', '');

        switch ($type) {
            case 'buy_get':
                $request->merge(['discount_number' => null]);
                $request->merge(['discount_type' => null]);
                $request->merge(['credits' => null]);
                break;
            case 'discount':
                $request->merge(['buy_get_get' => null]);
                $request->merge(['buy_get_buy' => null]);
                $request->merge(['credits' => null]);
                break;
            case 'credits':
                $request->merge(['discount_number' => null]);
                $request->merge(['discount_type' => null]);
                $request->merge(['buy_get_get' => null]);
                $request->merge(['buy_get_buy' => null]);
                break;
            default:
                $request->merge(['discount_number' => null]);
                $request->merge(['discount_type' => null]);
                $request->merge(['buy_get_get' => null]);
                $request->merge(['buy_get_buy' => null]);
                $request->merge(['credits' => null]);
                break;
        }

        return $request;
    }

    public static function confirmOffer(Brand $brand, Offer $offer)
    {
        $offers = Offer::where('brands_id', $brand->id)->pluck('id')->toArray();
        $id = $offer->id;

        return in_array($id, $offers) && $brand->id === $offer->brands_id;
    }
}
