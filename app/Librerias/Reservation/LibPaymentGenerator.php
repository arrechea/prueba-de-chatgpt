<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 21/06/18
 * Time: 15:07
 */

namespace App\Librerias\Reservation;


use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\Payment\PaymentType;
use App\Models\Purchase\Purchase;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Validator;

abstract class LibPaymentGenerator
{
    /**
     * @param Request  $request
     * @param Purchase $purchase
     * @param Company  $company
     * @param Location $location
     * @param bool     $isAdmin
     *
     * @return \Illuminate\Validation\Validator
     * @throws ValidationException
     */
    static private function validateRequest(Request $request, Purchase $purchase, Company $company, Location $location, bool $isAdmin)
    {
        $brand = $location->brand;
        /**
         * @var \Illuminate\Validation\Validator $validator
         */
        $validator = Validator::make($request->all(), [
            'users_id'         => [
                'required',
                Rule::exists('user_profiles', 'id')
                    ->where(function ($query) use ($company) {
                        $query->where('status', 'active');
                        $query->where('companies_id', $company->id);
                    }),
            ],
            'payment_types_id' => [
                'required',
                Rule::exists('brands_payment_types', 'payment_types_id')
                    ->where(function ($query) use ($isAdmin, $brand) {
                        $query->where('brands_id', $brand->id);
                        if ($isAdmin) {
                            $query->where('back', 1);
                        } else {
                            $query->where('front', 1);
                        }
                    }),
            ],
        ]);
        $validator->after(function ($validator) use (
            $purchase,
            $location
        ) {
            if ($purchase->locations_id !== $location->id) {
                $validator->errors()->add('purchase', __('reservation-fancy.error.NoPurchase'));
            }
        });

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator;
    }

    /**
     * @param Request  $request
     * @param Purchase $purchase
     * @param Company  $company
     * @param Location $location
     * @param bool     $isAdmin
     *
     * @return null
     * @throws ValidationException
     */
    static public function generate(Request $request, Purchase $purchase, Company $company, Location $location, bool $isAdmin)
    {
        if (!$purchase->needPayment()) {
            return null;
        }
        //Validate
        $validator = LibPaymentGenerator::validateRequest($request, $purchase, $company, $location, $isAdmin);

        /**
         * @var PaymentType $paymentType
         */
        $paymentType = $purchase->payment_type;
        $paymentData = (array)($request->get('payment_data', []));

        if ($paymentType) {
            $paymentSystem = $paymentType->getPaymentEspecificHandler();
            $paymentSystem->GenerateOrder($purchase, $paymentData, $validator);
        }
    }
}
