<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 09/05/2018
 * Time: 12:32 PM
 */

namespace App\Librerias\Payments\PaymentTypes\Conekta;

use App\Librerias\Payments\PaymentMethod;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\Purchase\Purchase;
use App\Models\Subscriptions\Subscribable;
use App\Models\Subscriptions\UserRecurrentPayment;
use App\Models\User\UserProfile;
use App\Models\User\UsersConekta;
use Conekta\Conekta as ConektaSDK;
use Conekta\Customer;
use Conekta\Handler;
use Conekta\Order;
use Conekta\ParameterValidationError;
use Conekta\ProccessingError;
use Conekta\ProcessingError;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use Validator as ValidatorMaker;

class ConektaOld extends PaymentMethod
{
    protected $payment_slug = 'conekta';
    protected $method;

    /**
     * @param Brand|null $brand
     *
     * @return string
     * @throws \Throwable
     */
    public function ConfigurationInputs(Brand $brand = null): string
    {
        $settings = null;
        if ($brand) {
            $this->setMethod($brand);

            $settings = $this->getConfig();
        }

        return VistasGafaFit::view('admin.company.Brands.payments.conekta', [
            'name'     => $this->name,
            'slug'     => $this->payment_slug,
            'brand'    => $brand,
            'settings' => $settings,
            'method'   => $this->method,
        ])->render();
    }

    /**
     * @param Purchase  $purchase
     * @param array     $paymentData
     * @param Validator $validator
     *
     * @throws ValidationException
     */
    public function GenerateOrder(Purchase $purchase, array $paymentData, Validator $validator)
    {
        //Seteamos correctamente el method
        $this->setMethod($purchase->brand);

        $privateKey = $this->getPrivateKey();
        $userProfile = $purchase->user_profile;
        $cardToken = $paymentData['card']['id'] ?? null;
        $sourceCard = isset($paymentData['sourceCard']) && $paymentData['sourceCard'] === 'true';

        $validator->after(function ($validator) use (
            $privateKey,
            $userProfile
        ) {
            if (!$privateKey || !$userProfile) {
                $validator->errors()->add('payment_id', __('reservation-fancy.error.conekta.NotConfigured'));
            }
        });
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        //Update User
        $this->updateUser($userProfile, $paymentData);

        //Iniciar Conekta
        $this->initConekta($privateKey);
        //Get User (Tener en cuenta el token utilizado)
        $customer = $this->getConektaUserByUserProfile($privateKey, $userProfile);

        if (!$customer) {
            //Si no hay user crearlo
            $customer = $this->createUser($privateKey, $userProfile, $validator);
        }
        //Si queremos salvar la tarjeta la salvamos
        if ($this->userWantSaveCard($paymentData)) {
            $cardToken = $this->saveCard($customer, $cardToken, $validator);
            $sourceCard = true;
        }
        //Pagar
        $order = $this->makeOrder($purchase, $customer, $cardToken, $sourceCard, $validator);

        //salvar id de la orden en pago
        $purchase->payment_data_id = $order->id;
        $purchase->save();
    }

    /**
     * @param UserProfile $userProfile
     * @param array       $paymentData
     */
    private function updateUser(UserProfile $userProfile, array $paymentData)
    {

        //Actualizar Usuario
        $userProfile->phone = $paymentData['phone'] ?? $userProfile->phone;
        $userProfile->address = $paymentData['address'] ?? $userProfile->address;
        $userProfile->external_number = $paymentData['external_number'] ?? $userProfile->external_number;
        $userProfile->internal_number = $paymentData['internal_number'] ?? $userProfile->internal_number;
        $userProfile->postal_code = $paymentData['postal_code'] ?? $userProfile->postal_code;
        $userProfile->countries_id = $paymentData['countries_id'] ?? $userProfile->countries_id;
        $userProfile->save();
    }

    private function onlyVirtualProducts(): bool
    {
        $config = $this->getConfig();
        $mode = isset($config['only_virtual_products']) ? $config['only_virtual_products'] : false;

        return $mode === 'on';
    }

    /**
     * @param Purchase  $purchase
     * @param Customer  $customer
     * @param           $cardToken
     * @param bool      $sourceCard
     * @param Validator $validator
     *
     * @return mixed|null
     * @throws ValidationException
     */
    private function makeOrder(Purchase $purchase, Customer $customer, $cardToken, bool $sourceCard, Validator $validator)
    {
        $error = false;
        $order = null;
        $onlyVirtualProducts = $this->onlyVirtualProducts();


        $userProfile = $purchase->user_profile;
        $currencyCode = $purchase->brand->currency->code3;
        $countryCode = $userProfile->country ? $userProfile->country->code2 : '';

        $orderOptions = array(
            "line_items"    => $this->generateLineItemsByPurchase($purchase), //line_items
            "currency"      => $currencyCode,
            "customer_info" => array(
                "customer_id" => $customer->id,
            ), //customer_info
            "metadata"      => array("reference" => $purchase->id),
            "charges"       => array(
                array(
                    "payment_method" => !$sourceCard ?
                        array(//card with token
                            "type"     => "card",
                            "token_id" => $cardToken,
                        ) :
                        array(//saved card
                            "type"              => "card",
                            "payment_source_id" => $cardToken,
                        )//payment_method - use customer's default - a card
                    //to charge a card, different from the default,
                    //you can indicate the card's source_id as shown in the Retry Card Section
                ) //first charge
            ) //charges
        );//order

        if (!$onlyVirtualProducts) {
            $orderOptions['shipping_lines'] = array(
                array(
                    "amount"  => 0,
                    "carrier" => "custom",
                ),
            ); //shipping_lines - physical goods only
            $orderOptions['shipping_contact'] = array(
                "address" => array(
                    "street1"     => "{$userProfile->address} {$userProfile->external_number}, {$userProfile->internal_number}",
                    "postal_code" => $userProfile->postal_code,
                    "country"     => $countryCode,
                )//address
            ); //shipping_contact - required only for physical goods
        }
        if ($purchase->hasDiscountCode()) {
            $orderOptions['discount_lines'] = [
                [
                    'code'   => $purchase->discountCode->code,
                    'amount' => (int)($purchase->calculateDiscountOfTheDiscountCode($purchase->discountCode) * 100),
                    'type'   => 'sign',
                ],
            ];
        }

        try {
            $order = Order::create($orderOptions);
        } catch (ProcessingError $error) {
            $error = $error->getMessage();
        } catch (ParameterValidationError $error) {
            $error = $error->getMessage();
        } catch (Handler $error) {
            $error = $error->getMessage();
        }
        if (!$order) {
            $validator->after(function ($validator) use ($error) {
                $validator->errors()->add('order', $error);
            });
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        }

        return $order;
    }

    /**
     * @param Purchase $purchase
     *
     * @return array
     */
    private function generateLineItemsByPurchase(Purchase $purchase): array
    {
        $response = [];
        $items = $purchase->items;
        if ($items->count() > 0) {
            $response = $items->map(function ($itemPurchase) {
                return
                    [
                        "name"       => $itemPurchase->item_name,
                        "unit_price" => (int)(((float)($itemPurchase->item_price_final)) * 100),
                        "quantity"   => (int)($itemPurchase->quantity),
                    ];
            })->values()->toArray();
        }

        return $response;
    }

    /**
     * @param Customer  $customer
     * @param string    $cardToken
     * @param Validator $validator
     *
     * @return string
     * @throws ValidationException
     */
    private function saveCard(Customer $customer, string $cardToken, Validator $validator)
    {
        if (!$cardToken) {
            $validator->after(function ($validator) {
                $validator->errors()->add('cardToken', __('reservation-fancy.error.isFull'));
            });
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        }

        $source = $customer->createPaymentSource(array(
            'token_id' => $cardToken,
            'type'     => 'card',
        ));

        return $source->id;
    }

    /**
     * @param Customer  $customer
     * @param string    $cardToken
     * @param Validator $validator
     *
     * @return mixed
     * @throws ValidationException
     */
    private function removeCard(Customer $customer, string $cardToken, Validator $validator)
    {
        if (!$cardToken) {
            $validator->after(function ($validator) {
                $validator->errors()->add('cardToken', __('reservation-fancy.error.isFull'));
            });
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        }
        $customer->deletePaymentSourceById($cardToken);

        return true;
    }

    /**
     * @param Brand       $brand
     * @param UserProfile $userProfile
     * @param             $option
     *
     * @return string
     * @throws ValidationException
     */
    public function addPaymentOption(Brand $brand, UserProfile $userProfile, $option)
    {
        $validator = ValidatorMaker::make([
            'cardToken' => $option,
        ], [
            'cardToken' => 'required|string',
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        //Seteamos correctamente el method
        $this->setMethod($brand);
        $privateKey = $this->getPrivateKey();

        //Iniciar Conekta
        $this->initConekta($privateKey);

        //Get User (Tener en cuenta el token utilizado)
        $customer = $this->getConektaUserByUserProfile($privateKey, $userProfile);
        if (!$customer) {
            //Si no hay user crearlo
            $customer = $this->createUser($privateKey, $userProfile, $validator);
        }

        $cardToken = $this->saveCard($customer, $option, $validator);

        return $cardToken;
    }

    /**
     * @param Brand       $brand
     * @param UserProfile $userProfile
     * @param             $option
     *
     * @throws ValidationException
     * @return mixed
     */
    public function removePaymentOption(Brand $brand, UserProfile $userProfile, $option)
    {
        $validator = ValidatorMaker::make([
            'cardToken' => $option,
        ], [
            'cardToken' => 'required|string',
        ]);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        //Seteamos correctamente el method
        $this->setMethod($brand);
        $privateKey = $this->getPrivateKey();

        //Iniciar Conekta
        $this->initConekta($privateKey);

        //Get User (Tener en cuenta el token utilizado)
        $customer = $this->getConektaUserByUserProfile($privateKey, $userProfile);
        if (!$customer) {
            //Si no hay user crearlo
            $customer = $this->createUser($privateKey, $userProfile, $validator);
        }

        $cardToken = $this->removeCard($customer, $option, $validator);

        return $cardToken;
    }

    /**
     * @param array $paymentData
     *
     * @return bool
     */
    private function userWantSaveCard(array $paymentData): bool
    {
        $wantSave = ($paymentData['saveCard'] ?? 'false') === 'true';

        return $wantSave;
    }

    /**
     * @param string $privateKey
     */
    private function initConekta(string $privateKey)
    {
        require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'Conekta.php';
        ConektaSDK::setApiKey($privateKey);
        ConektaSDK::setApiVersion("2.0.0");
    }

    /**
     * @param string      $privateKey
     * @param UserProfile $userProfile
     *
     * @param Validator   $validator
     *
     * @return Customer
     * @throws ValidationException
     */
    private function createUser(string $privateKey, UserProfile $userProfile, Validator $validator): Customer
    {
        $error = null;
        $customer = null;
        try {
            $customer = Customer::create(
                array(
                    "name"  => "{$userProfile->first_name} {$userProfile->last_name}",
                    "email" => $userProfile->email,
                    "phone" => $userProfile->phone,
                )//customer
            );
        } catch (ProcessingError $error) {
            $error = $error->getMesage();
        } catch (ParameterValidationError $error) {
            $error = $error->getMessage();
        } catch (Handler $error) {
            $error = $error->getMessage();
        }
        if (!$customer) {
            $validator->after(function ($validator) use ($error) {
                $validator->errors()->add('phone', $error);
            });
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        }
        //Create in DB
        $userConekta = new UsersConekta();
        $userConekta->private_key = $this->encodeKey($privateKey);
        $userConekta->user_profiles_id = $userProfile->id;
        $userConekta->users_id = $userProfile->users_id;
        $userConekta->user_token = $customer->id;
        $userConekta->save();

        //return
        return $customer;
    }

    /**
     * @param string      $privateKey
     * @param UserProfile $userProfile
     *
     * @return Customer|null|string
     */
    private function getConektaUserByUserProfile(string $privateKey, UserProfile $userProfile): ?Customer
    {
        $customer = null;
        $userProfileId = $userProfile->id;
        $tokenToSearch = $this->encodeKey($privateKey);

        /**
         * @var UsersConekta $infoConektaEnDB
         */
        $infoConektaEnDB = UsersConekta::where('user_profiles_id', $userProfileId)
            ->where('private_key', $tokenToSearch)
            ->select('user_token')
            ->first();

        if ($infoConektaEnDB) {
            $error = false;
            try {
                $customer = Customer::find($infoConektaEnDB->user_token);
            } catch (ProcessingError $error) {
                $error = true;
            } catch (ParameterValidationError $error) {
                $error = true;
            } catch (Handler $error) {
                $error = true;
            }
            if ($error || !$customer) {
                UsersConekta::where('user_profiles_id', $userProfileId)
                    ->where('private_key', $tokenToSearch)
                    ->delete();
            }
        }

        return $customer;
    }

    /**
     * @param string $privateKey
     *
     * @return string
     */
    private function encodeKey(string $privateKey): string
    {
        return md5($privateKey);
    }

    /**
     * @return string
     */
    private function getPublicKey(): string
    {
        $config = $this->getConfig();
        $mode = isset($config['type']) ? $config['type'] : 'development';

        switch ($mode) {
            case 'production':
                $key = isset($config['production_public_api_key']) ? $config['production_public_api_key'] : '';
                break;
            default:
                $key = isset($config['development_public_api_key']) ? $config['development_public_api_key'] : '';
                break;
        }

        return $key;
    }

    /**
     * @return string
     */
    private function getPrivateKey(): string
    {
        $config = $this->getConfig();
        $mode = isset($config['type']) ? $config['type'] : 'development';

        switch ($mode) {
            case 'production':
                $key = isset($config['production_private_api_key']) ? $config['production_private_api_key'] : '';
                break;
            default:
                $key = isset($config['development_private_api_key']) ? $config['development_private_api_key'] : '';
                break;
        }

        return $key;
    }

    /**
     * @param Brand       $brand
     * @param UserProfile $userProfile
     *
     * @return Collection
     */
    public function getPaymentUserInfoInBrand(Brand $brand, UserProfile $userProfile)
    {
        $response = new Collection([]);
        //Seteamos correctamente el method
        $this->setMethod($brand);
        //Iniciamos conexion
        $privateKey = $this->getPrivateKey();
        $this->initConekta($privateKey);

        //Customer
        $customer = $this->getConektaUserByUserProfile($privateKey, $userProfile);
        if ($customer) {
            $paymentSource = (array)($customer->payment_sources);
            if ($paymentSource) {
                foreach ((array)$paymentSource as $source) {
                    if ($source->isCard()) {
                        $response->push((array)$source);
                    }
                }
            }
        }

        return $response->values();
    }
}
