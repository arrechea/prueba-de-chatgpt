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
class Paypal extends PaymentMethod
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
        $this->checkPaymentInGafaPay($purchase);
    }
}
