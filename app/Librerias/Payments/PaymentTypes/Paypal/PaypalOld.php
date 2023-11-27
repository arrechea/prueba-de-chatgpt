<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 09/05/2018
 * Time: 12:12 PM
 */

namespace App\Librerias\Payments\PaymentTypes\Paypal;

use App\Librerias\Payments\PaymentMethod;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Purchase\Purchase;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;
use PayPal\Api\Payment;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;
use Log;

/**
 * Keys cuenta wisquimas test
 *
 * public: 'ARg7Pcp8rzYCpjlU4_ninRdMXI0NkgQ1vLa8mbDlH7f6HP3APEnw9aSQChNGhHbPSt5U4AHmiPF3YXC-',
 * private: 'EDvmzUBu09NUX0HHTFKnMJdDyvgk5IR7zBSPunA9fbH09PoTyRLhlj4UV1C8gHuZRCEvTQhkL6CppBOz'
 */
class PaypalOld extends PaymentMethod
{
    protected $name = 'Paypal';
    protected $payment_slug = 'paypal';

    /**
     * @param Brand|null $brand
     *
     * @return string
     */
    public function ConfigurationInputs(Brand $brand = null): string
    {
        $settings = null;
        if ($brand) {
            $this->setMethod($brand);

            $settings = $this->getConfig();
        }

        return VistasGafaFit::view('admin.company.Brands.payments.paypal', [
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
     * @return void
     * @throws ValidationException
     */
    public function GenerateOrder(Purchase $purchase, array $paymentData, Validator $validator)
    {
        $validator->after(function ($validator) use (
            $purchase,
            $paymentData
        ) {
            //Comprobar si existe la referencia de pago
            $paypalPaymentId = $this->getPaymentIdInPaypal($paymentData);
            if (!$paypalPaymentId) {
                $validator->errors()->add('payment_id', __('reservation-fancy.error.paypal.NoPaymentId'));
            } else {
                //Comprobar si en la base de datos ya se registro otro pago con esa misma referencia
                if ($this->isReferenceReused($paypalPaymentId)) {
                    $validator->errors()->add('payment_id', __('reservation-fancy.error.paypal.ReusedReference'));
                } else {
                    //Seteamos correctamente el metodo
                    $this->setMethod($purchase->brand);

                    //Comprobar status de pago en paypal
                    if (!$this->isVerifiedWithPaypal($paypalPaymentId)) {
                        $validator->errors()->add('payment_id', __('reservation-fancy.error.paypal.DontVerified'));
                    } else {
                        //Almacenar referencia de pago
                        $purchase->payment_data_id = $paypalPaymentId;
                        $purchase->save();
                    }
                }
            }
        });

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * @param string $reference
     *
     * @return bool
     */
    public function isVerifiedWithPaypal(string $reference): bool
    {
        $response = false;
        //conectarse con Paypal
        $apiContext = $this->connection();
//        Log::info("Solicitud de verificacion a paypal #$reference");
//        $publicKey = $this->getPublicKey();
//        $privateKey = $this->getPrivateKey();
//        Log::info("publicKey $publicKey");
//        Log::info("privateKey $privateKey");

        try {
            $payment = Payment::get($reference, $apiContext);
            if ($payment->getState() === 'approved') {
                $response = true;
            }
//            Log::info(['state' => $payment->getState()]);
        } catch (PayPalConnectionException $e) {
//            Log::error($e->getMessage());
        }

        //recibir pago
        return $response;
    }

    /**
     * @return ApiContext
     */
    public function connection()
    {
        require __DIR__ . '/PayPal-PHP-SDK/autoload.php';

        $publicKey = $this->getPublicKey();
        $privateKey = $this->getPrivateKey();

        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                $publicKey,
                $privateKey
            )
        );
        if($this->isLiveMode()){
            $apiContext->setConfig(
                array(
                    'mode' => 'live',
                )
            );
        }

        return $apiContext;
    }

    /**
     * @return bool
     */
    public function isLiveMode()
    {
        $config = $this->getConfig();
        $mode = isset($config['type']) ? $config['type'] : 'development';

        return $mode === 'production';
    }

    /**
     * @return string
     */
    public function getPublicKey(): string
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
    public function getPrivateKey(): string
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
     * @param string $reference
     *
     * @return bool
     */
    public function isReferenceReused(string $reference): bool
    {
        return Purchase::where('payment_data_id', $reference)->count() > 0;
    }

    /**
     * @param array $paymentData
     *
     * @return string|null
     */
    public function getPaymentIdInPaypal(array $paymentData)
    {
        return $paymentData['id']??null;
    }
}
