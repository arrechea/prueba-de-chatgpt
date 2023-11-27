<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 11/06/18
 * Time: 17:24
 */

namespace App\Librerias\Reservation;


use App\Events\Purchases\PurchaseCreated;
use App\Librerias\DiscountCode\LibDiscountCode;
use App\Models\Admin\AdminProfile;
use App\Models\Combos\Combos;
use App\Models\Company\Company;
use App\Models\DiscountCode\DiscountCode;
use App\Models\Location\Location;
use App\Models\Membership\Membership;
use App\Models\Products\Product;
use App\Models\Payment\PaymentType;
use App\Models\Purchase\Purchase;
use App\Models\User\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule;
use Validator;

abstract class LibGeneratePurchase
{
    /**
     * @param Request  $request
     * @param Company  $company
     * @param Location $location
     *
     * @param bool     $isAdmin
     *
     * @param bool     $validate
     *
     * @return \Illuminate\Validation\Validator
     * @throws ValidationException
     */
    static private function validateRequest(Request $request, Company $company, Location $location, bool $isAdmin, bool $validate = true)
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
            'memberships_id'   => [
                'nullable',
                Rule::exists('memberships', 'id')
                    ->where(function ($query) use ($company) {
                        $query->where('status', 'active');
                        $query->where('companies_id', $company->id);
                    }),
            ],
            'combos_id'        => [
                'nullable',
                Rule::exists('combos', 'id')
                    ->where(function ($query) {
                        $query->where('status', 'active');
                    }),
            ],
            'payment_types_id' => [
                'nullable',
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
            'giftCode'         => [
                'nullable',
                Rule::unique('purchase_gift_cards', 'code')
                    ->where(function ($query) use ($isAdmin, $brand) {
                        $query->where('brands_id', $brand->id);
                        $query->where('is_active', 1);
                    }),
            ],
        ], [
            'users_id.exists'         => __('messages.reservation-users_id'),
            'memberships_id.exists'   => __('messages.reservation-memberships_id'),
            'combos_id.exists'        => __('messages.reservation-combos_id'),
            'payment_types_id.exists' => __('messages.reservation-payment_types_id'),
            'giftCode.unique'         => __('messages.reservation-gift_card-taken'),
        ]);
        if ($validate) {
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        }

        return $validator;
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Location $location
     * @param bool     $isAdmin
     *
     * @param bool     $subscribe
     *
     * @return Purchase|null
     * @throws ValidationException
     */
    static public function create(Request $request, Company $company, Location $location, bool $isAdmin, bool $subscribe = false)
    {
        //Validate
        $validator = LibGeneratePurchase::validateRequest($request, $company, $location, $isAdmin, false);
        //Get variables
        $admin = null;
        $membership = null;
        $combo = null;
        $product = null;
        $payment = null;
        $giftCode = $request->get('giftCode', null);
        $discountCodeString = $request->get('discountCode', '');
        $discountCode = null;

        if ($isAdmin) {
            //Es administrador
            $admin = Auth::user()->getProfileInThisCompany();
            $user = UserProfile::where('id', $request->get('users_id'))
                ->where('companies_id', $company->id)
                ->first();
        } else {
            //No es administrador
            $user = Auth::user()->getProfileInThisCompany();
        }

        $membership_arr = $request->get('memberships_id');
        $membershipamount_arr = $request->get('memberships_amounts');
        if ($membership_arr && count($membership_arr) > 0) {
            $membership = array();
            $membership_amount = array();
            $contador = 0;
            foreach ($membership_arr as $memberships_id) {
                $memberships_item = Membership::where('id', $memberships_id)
                    ->where('brands_id', $location->brand->id)
                    ->where('status', 'active')
                    ->whereNull('deleted_at')
                    ->first();
                $membership[ $memberships_id ] = $memberships_item;
                $membership_amount[ $memberships_id ] = $membershipamount_arr[ $contador ];
                $contador++;
            }


        }

        $combo_arr = $request->get('combos_id');
        $comboamount_arr = $request->get('combos_amounts');
        if ($combo_arr && count($combo_arr) > 0) {
            $combo = array();
            $combo_amount = array();
            $contador_combo = 0;
            foreach ($combo_arr as $combos_id) {
                $combos_item = Combos::where('id', $combos_id)
                    ->where('brands_id', $location->brand->id)
                    ->where('status', 'active')
                    ->whereNull('deleted_at')
                    ->first();
                $combo[ $combos_id ] = $combos_item;
                $combo_amount[ $combos_id ] = $comboamount_arr[ $contador_combo ];
                $contador_combo++;
            }

        }

        $product_arr = $request->get('products_id');
        $productamount_arr = $request->get('products_amounts');
        if ($product_arr && count($product_arr) > 0) {
            $product = array();
            $product_amount = array();
            $contador_product = 0;
            foreach ($product_arr as $products_id) {
                $products_item = Product::where('id', $products_id)
                    ->where('brands_id', $location->brand->id)
                    ->whereNull('deleted_at')
                    ->first();
                $product[ $products_id ] = $products_item;
                $product_amount[ $products_id ] = $productamount_arr[ $contador_product ];
                $contador_product++;
            }

        }

        if ($request->has('payment_types_id')) {
            $payment = PaymentType::where('id', $request->get('payment_types_id'))
                ->first();
        }
        /*
         * Final Validation
         */
        $validator->after(function ($validator) use (
            $isAdmin,
            $admin,
            $user,
            $membership,
            $combo,
            $product
        ) {
            if (!$user) {
                $validator->errors()->add('user', __('reservation-fancy.error.user'));
            }
            if ($isAdmin && !$admin) {
                $validator->errors()->add('admin', __('reservation-fancy.error.admin'));
            }
        });
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        //No hay nada para comprar
        if (!$membership && !$combo && !$product) {
            return null;
        }
        //Combos
        if ($combo) {
            $validator->after(function ($validator) use (
                $user,
                $location,
                $subscribe,
                $combo
            ) {
                foreach ($combo as $combo_item) {
                    if ($subscribe && $user->hasAnSubscription($location, $combo_item)) {
                        $validator->errors()->add('user', __('reservation-fancy.error.product.subscribed'));
                    }
                    if (!$user->canBuy($combo_item)) {
                        $validator->errors()->add('user', __('reservation-fancy.error.product.limit'));
                    }
                    if (!$combo_item->isValidForUserCategories($user)) {
                        $validator->errors()->add('user', __('reservation-fancy.error.product.restricted'));
                    }
                }
            });
        }
        //Memberships
        if ($membership) {
            $validator->after(function ($validator) use (
                $user,
                $location,
                $subscribe,
                $membership
            ) {
                foreach ($membership as $membership_item) {
                    if ($membership_item->global_purchase >= 1) {
                        if ($subscribe && $user->hasAnSubscription($location, $membership_item)) {
                            $validator->errors()->add('user', __('reservation-fancy.error.product.subscribed'));
                        }
                        if (
                            !$user->canBuy($membership_item)
                            ||
                            $membership_item->total_purchase >= $membership_item->global_purchase
                            ||
                            $user->hasThisMembershipInLocation($location, $membership_item)
                        ) {
                            $validator->errors()->add('user', __('reservation-fancy.error.product.limit'));
                        }
                    }
                }
                foreach ($membership as $membership_item) {
                    if (!$membership_item->isValidForUserCategories($user)) {
                        $validator->errors()->add('user', __('reservation-fancy.error.product.restricted'));
                    }
                }

            });
        }

        //DiscountCode
        if (!empty($discountCodeString)) {
            $validator->after(function ($validator) use (
                $request,
                $discountCodeString,
                $location,
                $user,
                &$discountCode,
                $combo,
                $membership,
                $product
            ) {
                if (!$combo && !$membership && !$product) {
                    $validator->errors()->add('discountCode', __('reservation-fancy.error.discount_code.unknown'));
                } else {
                    $purchase_item = array();
                    if ($combo) {
                        foreach ($combo as $combo_item) {
                            $purchase_item = $combo_item;
                        }

                    } else if ($membership) {
                        foreach ($membership as $membership_item) {
                            $purchase_item = $membership_item;
                        }

                    } else if ($product) {
                        foreach ($product as $product_item) {
                            $purchase_item = $product_item;
                        }

                    }

                    $discountCode = LibDiscountCode::checkoutDiscountCodeValid($discountCodeString, $location->brand, $user, $purchase_item);

                    if (!$discountCode) {
                        $validator->errors()->add('discountCode', __('reservation-fancy.error.discount_code.unknown'));
                    }
                }
            });
        }

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        if ($request->get('test', false) === 'true') {
            // parar aqui porque terminan validaciones
            return null;
        }
        //crear linea de purchase
        $purchase = self::createPurchase($payment, $location, $user, $admin, $giftCode, $discountCode);
        $purchase->payment_data_id = $request->get('payment_data', null);

        if ($combo) {
            foreach ($combo as $combo_item) {
                if ($user->canBuy($combo_item)) {
                    $purchase->addItem($combo_item, $combo_amount[ $combo_item->id ]);
                }
            }

        }

        if ($membership) {
            foreach ($membership as $membership_item) {
                if ($user->canBuy($membership_item)) {
                    $purchase->addItem($membership_item, $membership_amount[ $membership_item->id ]);

                    if ($membership_item->total_purchase < $membership_item->global_purchase) {
                        $membership_item->total_purchase = ++$membership_item->total_purchase;
                        $membership_item->save();
                    }
                }
            }

        }
        if ($product) {
            foreach ($product as $product_item) {
                if ($user->canBuy($product_item)) {
                    $purchase->addItem($product_item, $product_amount[ $product_item->id ]);
                }

                //$purchase->addItem($product_item,$product_amount[$product_item->id]);

                /*if ($product_item->total_purchase < $product_item->global_purchase) {
                    $product_item->total_purchase = ++$product_item->total_purchase;
                    $product_item->save();
                }*/
            }

        }
        if ($purchase->isGiftCard()) {
            $purchase->addGiftCard($giftCode);
        }

        if ($purchase->hasDiscountCode()) {
            $purchase->addDiscountCode($discountCode);
        }

        $purchase->updateTotals();

        //crear productos internos
        return $purchase;
    }

    /**
     * @param PaymentType|null  $paymentType
     * @param Location          $location
     * @param UserProfile       $profile
     * @param AdminProfile|null $adminProfile
     *
     * @param null              $giftCode
     *
     * @return Purchase
     */
    private static function createPurchase(PaymentType $paymentType = null, Location $location, UserProfile $profile, AdminProfile $adminProfile = null, $giftCode = null, $discountCode = null): Purchase
    {
        $brand = $location->brand;
        $purchase = new Purchase();

        $purchase->payment_types_id = $paymentType ? $paymentType->id : null;
        $purchase->currencies_id = $brand->currencies_id;

        $purchase->status = $purchase::$statusPending;
        $purchase->is_gift_card = $giftCode ? true : false;
        $purchase->has_discount_code = !is_null($discountCode) ? true : false;

        $purchase->locations_id = $location->id;
        $purchase->brands_id = $location->brands_id;
        $purchase->companies_id = $location->companies_id;

        $purchase->user_profiles_id = $profile->id;
        $purchase->users_id = $profile->users_id;
        $purchase->user_profiles_categories = $profile->generateUserCategoriesString();
        $purchase->user_profiles_email = $profile->email??'';


        $purchase->admin_profiles_id = $adminProfile ? $adminProfile->id : null;
        $purchase->save();


        return $purchase;
    }
}
