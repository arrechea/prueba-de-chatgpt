<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 10/05/2018
 * Time: 10:35 AM
 */

namespace App\Librerias\Payments;


use App\Models\Brand\Brand;
use App\Models\Payment\PaymentType;
use App\Models\User\UserProfile;
use Illuminate\Support\Collection;

class LibPayments
{
    /**
     * @param Brand|null $brand
     *
     * @return string
     */
    public static function paymentView(Brand $brand = null): string
    {
        $view = '';
//        $brand_methods = $brand->payment_types;
        $methods = SystemPaymentMethods::get()->map(function ($item) {
            return new $item->model($item);
        });

        foreach ($methods as $method) {
            $view .= $method->ConfigurationInputs($brand);
        }

        return $view;
    }

    /**
     * @param string $slug
     *
     * @return null
     */
    public static function methodBySlug(string $slug)
    {
        /**
         * @var PaymentType $method
         */
        $method = PaymentType::where('slug', $slug)->first();

        return isset($method) ? $method->id : null;
    }

    /**
     * @param UserProfile $userProfile
     * @param Brand       $brand
     * @param array       $paymentTypes
     *
     * @return Collection
     */
    public static function getPaymentUserInfoInBrand(UserProfile $userProfile, Brand $brand, $paymentTypes = []): Collection
    {
        $response = new Collection([]);
        if ($paymentTypes && $paymentTypes->count() > 0) {
            $paymentTypes->each(function ($paymentType) use (&$response, $brand, $userProfile) {
                /**
                 * @var PaymentMethodInterface $modelInfo
                 */
                $modelInfo = new $paymentType->model($paymentType);
                $response->put($paymentType->slug, $modelInfo->getPaymentUserInfoInBrand($brand, $userProfile));
            });
        }

        return $response;
    }


}
