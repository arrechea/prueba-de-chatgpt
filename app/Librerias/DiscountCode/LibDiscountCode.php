<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 28/09/18
 * Time: 11:00
 */

namespace App\Librerias\DiscountCode;


use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Credit\Credit;
use App\Models\DiscountCode\DiscountCode;
use App\Models\DiscountCode\DiscountCodesCredits;
use App\Models\Purchase\Purchasable;
use App\Models\User\UserProfile;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\AdminRequest as Request;
use Validator;

abstract class LibDiscountCode
{
    /**
     * @param string $code
     * @param Brand  $brand
     *
     * @return DiscountCode|null
     */
    static public function getDiscountCodeInBrand(string $code, Brand $brand): ?DiscountCode
    {
        return DiscountCode::where('code', $code)
            ->where('status', 'active')
            ->where('brands_id', $brand->id)
            ->whereNull('deleted_at')
            ->first();
    }

    /**
     * @param string      $code
     * @param Brand       $brand
     *
     * @param UserProfile $userProfile
     *
     * @param Purchasable $purchase_item
     *
     * @return DiscountCode|null
     * @throws ValidationException
     */
    static public function checkoutDiscountCodeValid(string $code, Brand $brand, UserProfile $userProfile, Purchasable $purchase_item): ?DiscountCode
    {

        $validator = Validator::make([], [], []);

        $code = self::getDiscountCodeInBrand($code, $brand);
		
        $validator->after(function ($validator) use (
            $code,
            $userProfile,
            $purchase_item
        ) {
            if (!$code) {
                $validator->errors()->add('user', __('reservation-fancy.error.discount_code.invalid'));
            } else {
                if (!$code->isValidNow()) {
                    $validator->errors()->add('user', __('reservation-fancy.error.discount_code.not_in_range'));
                }
                if ($code->hasReachedLimit()) {
                    $validator->errors()->add('user', __('reservation-fancy.error.discount_code.has_reached_limit'));
                }
                if (!$code->isValidForUser($userProfile)) {
                    $validator->errors()->add('user', __('reservation-fancy.error.discount_code.has_reached_limit'));
                }
				if (!$code->isValidForUserCategories($userProfile)) {
                    $validator->errors()->add('user', __('reservation-fancy.error.discount_code.not_in_user_category'));
                }
            }
        });


        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $code;
    }

    public static function saveCredits(Request $request, Company $company, Brand $brand, DiscountCode $discountCode)
    {
        $credits_array = array_where($request->get('credits'), function ($v) {
            return isset($v['active']) && $v['active'] === 'on';
        });

        $saved = [];

        foreach ($credits_array as $credit) {
            if (isset($credit['id'])) {
                $real_credit = Credit::find($credit['id']);

                    $saved_credit = DiscountCodesCredits::updateOrCreate([
                        'credits_id'        => $real_credit->id,
                        'discount_codes_id' => $discountCode->id,
                    ]);

                    $saved[] = $saved_credit->credits_id;

            }
        }

        DiscountCodesCredits::whereNotIn('credits_id', $saved)->where('discount_codes_id', $discountCode->id)->delete();
    }
}
