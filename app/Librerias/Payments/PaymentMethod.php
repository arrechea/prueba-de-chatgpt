<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 09/05/2018
 * Time: 12:05 PM
 */

namespace App\Librerias\Payments;


use App\Librerias\Errores\LibErrores;
use App\Librerias\GafaPay\LibGafaPay;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Payment\PaymentType;
use App\Models\Purchase\Purchase;
use App\Models\User\UserProfile;

abstract class PaymentMethod implements PaymentMethodInterface
{
    protected $name;
    protected $payment_slug = 'undefined';
    protected $brand;
    protected $id;
    protected $method;

    // gafapay
    protected $gafapayBrandId;
    protected $gafapayClientId;
    protected $gafapayClientSecret;

    /**
     * PaymentMethod constructor.
     *
     * @param PaymentType|null $method
     */
    public final function __construct(PaymentType $method = null, $gafapayBrandId = null, $gafapayClientId = null, $gafapayClientSecret = null)
    {
        $slug = $this->payment_slug;
        $this->gafapayBrandId = $gafapayBrandId;
        $this->gafapayClientId = $gafapayClientId;
        $this->gafapayClientSecret = $gafapayClientSecret;

        $method = $method ? $method : PaymentType::where('slug', $slug)->first();
        if ($method) {
            $this->name = $method->name;
            $this->id = $method->id;
            $this->method = $method;
        }
    }

    /**
     * @param Brand       $brand
     * @param UserProfile $userProfile
     *
     * @return null
     */
    public function getPaymentUserInfoInBrand(Brand $brand, UserProfile $userProfile)
    {
        return null;
    }

    /**
     * @param Brand|null $brand
     *
     * @return string
     */
    public function ActivationCheckboxes(Brand $brand = null)
    {
        $settings = null;
        if ($brand) {
            $this->setMethod($brand);

            $settings = $this->getConfig();
        }

        return VistasGafaFit::view('admin.company.Brands.payments.payment', [
            'name'   => $this->name,
            'slug'   => $this->payment_slug,
            'method' => $this->method,
        ])->render();
    }

    /**
     * @param Brand|null $brand
     *
     * @return string
     */
    public function ConfigurationInputs(Brand $brand = null): string
    {
        return '';
    }

    /**
     * @param string $slug
     *
     * @return bool
     */
    public function compareSlug(string $slug)
    {
        return $slug === $this->payment_slug;
    }

    /**
     * @param Brand $brand
     */
    protected function setMethod(Brand $brand)
    {
        $this->method = $brand->payment_types()->where('slug', $this->payment_slug)->first();
    }

    /**
     * @param Brand|null $brand
     *
     * @return mixed|null
     */
    public function getConfig(Brand $brand = null)
    {
        $slug = $this->payment_slug;

        $clientID = $brand->gafapay_client_id??$this->gafapayClientId;
        $clientSecret = $brand->gafapay_client_secret??$this->gafapayClientSecret;
        if (!$clientID || !$clientSecret)
            return null;

        $config = collect(LibGafaPay::clientPaymentSystems($clientID, $clientSecret))
            ->filter(function ($item) use ($slug) {
                return strtolower($item->metodoPago) == $slug;
            })
            ->first();

        if (!$config)
            return null;

        return [
            'type'                        => $config->entorno == 'prod' ? 'production' : 'development',
            'production_public_api_key'   => $config->llavePublicaApi, //apikey
            'development_public_api_key'  => $config->llavePublicaApiDesarrollo, //apikey_devmode
            'production_private_api_key'  => $config->llaveSecretaApi,//apisecretkey
            'development_private_api_key' => $config->llaveSecretaApiDesarrollo,// apisecretkey_devmode
            // extra
            'production_publishable_key'  => $config->llavePublicaWeb,// publickey
            'development_publishable_key' => $config->llavePublicaWebDesarrollo,// publickey_dev
            'only_virtual_products'       => $config->tieneEnvio,// publickey_dev
        ];
//        return isset($this->method->pivot->config) ? json_decode($this->method->pivot->config, true) : null;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Brand       $brand
     * @param UserProfile $userProfile
     * @param             $option
     *
     * @return mixed|void
     */
    public function addPaymentOption(Brand $brand, UserProfile $userProfile, $option)
    {

    }

    /**
     * @param Brand       $brand
     * @param UserProfile $userProfile
     * @param             $option
     *
     * @return mixed|void
     */
    public function removePaymentOption(Brand $brand, UserProfile $userProfile, $option)
    {

    }

    /**
     * @param Purchase $purchase
     */
    final public function checkPaymentInGafaPay(Purchase $purchase)
    {
        $token = $purchase->payment_data_id??'';
//        dd($purchase->toArray());
        $brand = $purchase->brand;

        $response = LibGafaPay::checkPaymentInGafaPay(
            $brand->gafapay_client_id,
            $brand->gafapay_client_secret,
            [
                'token' => $token,
                'extra' => json_encode(['purchase_id' => $purchase->id]),
            ]
        );
//        dd($response);
        $isCorrect = $response->response??false;
        $subscription = $response->subscription??false;

        if ($isCorrect !== 'true' && $isCorrect !== true) {
            LibErrores::lanzarErrores('El pago no pudo ser comprobado');
        }
        if ($subscription) {
            $purchase->subscription = $subscription;
            $purchase->save();
        }
    }

    /**
     * @return string
     */
    public function getPaymentSlug(): string
    {
        return $this->payment_slug;
    }
}
