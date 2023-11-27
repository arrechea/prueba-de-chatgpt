<?php

namespace App\Librerias\GafaPay;

use App\Librerias\Catalog\Tables\Company\CatalogBrand;
use App\Librerias\Payments\SystemPaymentMethods;
use App\Models\Payment\PaymentType;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LibGafaPay
{

    public static function request($endPoint, $method = 'GET', $options = [])
    {
        $client = new Client(['base_uri' => config('gafapay.api_url') . '/api/', 'http_errors' => false]);
        $response = $client->request($method, $endPoint, $options);
//        dd($response);
        $statusCode = $response->getStatusCode();
//        dd($statusCode);
        if ($statusCode == 500) {
            Log::error('internal error gafapay [500]');

            return null;
        }

        $data = json_decode($response->getBody()->getContents());
        if ($statusCode == 200 || $statusCode == 201) {
            return $data;
        }
//        dd($data);
        if ($statusCode >= 400 && $statusCode < 500) {
            $error = isset($data->error) ? (array)$data->error : 'Desconocido';
            if (is_array($error)) {
                $errorString = '';
                foreach ($error as $bloqueError) {
                    $bloqueError = (array)$bloqueError;
                    $errorString .= implode(',', $bloqueError) . ', ';
                }
                $error = $errorString;
            }

            Log::error("error gafapay [{$error}]");
        }

        return null;
    }

    public static function requestWithAuth($endPoint, $method = 'GET', $options = [])
    {
        if (!isset($options['headers'])) {
            $options['headers'] = ['Authorization' => "Bearer " . self::adminLogin()];
        }

        return self::request($endPoint, $method, $options);
    }

    public static function requestWithAuthBrand($clientId, $clientSecret, $endPoint, $method = 'GET', $options = [])
    {
        if (!isset($options['headers'])) {
            $options['headers'] = ['Authorization' => "Bearer " . self::brandLogin($clientId, $clientSecret)];
        }

        return self::request($endPoint, $method, $options);
    }

    public static function requestAsync($endPoint, $onSuccess, $onErr, $method = 'GET', $options = [])
    {
        $client = new Client(['base_uri' => config('gafapay.api_url') . '/api/']);

        return $client->requestAsync($method, $endPoint, $options)->then($onSuccess, $onErr);
    }

    public static function adminLogin()
    {
        return Cache::remember('gapapay.adminToken', 30, function () {
            $res = self::request('oauth/token', 'POST', [
                'form_params' => [
                    'grant_type'    => config('gafapay.grant_type'),
                    'client_id'     => config('gafapay.client_id'),
                    'client_secret' => config('gafapay.client_secret'),
                    'username'      => config('gafapay.username'),
                    'password'      => config('gafapay.password'),
                ],
            ]);

            if ($res) {
//                Cache::put('adminToken', $res[0]->access_token, now()->addMinutes(30));
                return $res[0]->access_token ?? null;
            }

            return null;
        });
    }

    public static function brandLogin($clientId, $clientSecret)
    {

//        return Cache::remember('gapapay.brandToken', 30, function () use ($clientId, $clientSecret) {
        $res = self::request('oauth/token', 'POST', [
            'form_params' => [
                'grant_type'    => 'client_credentials',
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
            ],
        ]);

        if ($res) {
            return $res[0]->access_token;
        }

        return null;
//        });
    }

    public static function createBrand($name, $description, $token, $onSuccess, $onErr)
    {
        self::requestAsync('brands', $onSuccess, $onErr, 'POST', [
            'form_params' => [
                'name'        => $name,
                'description' => (isset($description)) ? $description : $name,
            ],
            'headers'     => [
                'Content-Type'  => 'application/x-www-form-urlencoded',
                'Authorization' => "Bearer $token",
            ],
        ])->wait();
    }

    public static function updateBrand($name, $description, $brandID, $token, $onSuccess, $onErr)
    {
        self::requestAsync('brands/' . $brandID, $onSuccess, $onErr, 'PUT', [
            'form_params' => [
                'name'        => $name,
                'description' => $description,
            ],
            'headers'     => [
                'Content-Type'  => 'application/x-www-form-urlencoded',
                'Authorization' => "Bearer $token",
            ],
        ]);
    }


    public static function deleteBrand()
    {

    }

    public static function regenerateSecretBrand($id)
    {
        $endPoint = "brands.regeneratesecret/$id";

        try {
            $response = self::requestWithAuth($endPoint, 'POST', [
                'body'    => null,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
            ]);

            if (!$response)
                return null;

            return $response->data->oauth_client_secret;

        } catch (\Exception $e) {
            Log::error("error regeneratesecret brand gafapay. $e");

            return null;
        }
    }

    public static function exitsBrand()
    {
    }

    public static function processCreateBrand(CatalogBrand $brand)
    {
        $onSuccess = function ($response) use ($brand) {
            if ($response->getStatusCode() == 201) {
                $data = json_decode($response->getBody()->getContents());
                $brand->gafapay_client_id = $data->data->oauth_client_id;
                $brand->gafapay_client_secret = $data->data->oauth_client_secret;
                $brand->gafapay_brand_id = $data->data->id;
                Log::info("success created brand [{$data->data->oauth_client_id}]");
            }
        };

        $onErr = function ($res) {
//            dd($res);
            Log::error("error create brand gafapay. {$res}");
        };

        $token = self::adminLogin();

        if (isset($token)) {
            try {
                self::createBrand($brand->name, $brand->description, $token, $onSuccess, $onErr);
            } catch (\Exception $e) {
                Log::error("error create brand gafapay. [$e]");
            }

        }
    }

    static public function paymentSystems()
    {
        return Cache::remember('gapapay.paymentsystems', 30, function () {

            $response = self::requestWithAuth('paymentsystems', 'GET');

            if (!$response) return [];

            return collect($response->data)->map(function ($item, $key) {
                return ['name' => strtolower($item->nombre), 'id' => $item->id];
            })->pluck('id', 'name')->all();
        });
    }

    static public function paymentSystemsAll()
    {
        return Cache::remember('gapapay.paymentsystems.all', 30, function () {

            $response = self::requestWithAuth('paymentsystems', 'GET');

            if (!$response) return [];

            return $response->data;
        });
    }

    static public function createOrUpdatePymentSystemOnGafafit()
    {
        $paymentTypes = SystemPaymentMethods::get();
        $paymentGafapay = self::paymentSystemsAll();

//        dd($paymentGafapay);

        collect($paymentGafapay)->map(function ($item) use ($paymentTypes) {
            $obj = $paymentTypes->filter(function ($p) use ($item) {
                return $p->slug == strtolower($item->nombre);
            })->first();

            if (!$obj) {
                $obj = new PaymentType();
                $obj->name = $item->nombre;
                $obj->slug = strtolower($item->nombre);
                $obj->gafapay_id = $item->id;
                $obj->model = sprintf("App\Librerias\Payments\PaymentTypes\%s\%s", $item->nombre, $item->nombre);
                $obj->save();
            }

            if (!$obj->gafapay_id) {
                $obj->gafapay_id = $item->id;
                $obj->save();
            }

            return $obj;
        });
    }

    static public function brandPaymentSystems($brandId, $allData = false)
    {
        $response = self::requestWithAuth("brands/$brandId/paymentsystems", 'GET');
        if (!$response) return [];

        if ($allData == true)
            return $response->data;

        return collect($response->data)->map(function ($item, $key) {
            return ['name' => strtolower($item->nombre), 'id' => $item->id];
        })->pluck('id', 'name')->all();
    }

    static public function clientPaymentSystems($clientId, $clientSecret)
    {
        $response = self::requestWithAuthBrand($clientId, $clientSecret, "brand.paymentsystems", 'GET');
        if (!$response) return [];

        return $response->data;
    }

    static public function cancelSubscription($subscriptionId, $clientId, $clientSecret)
    {
        $response = self::requestWithAuthBrand($clientId, $clientSecret, "subscription.cancel/" . $subscriptionId, 'POST');
        if (!$response) return [];

        return $response->data;
    }

    /**
     * @param $brandId
     * @param $pymentSources
     *      [
     *      "conekta" => [
     *      "only_virtual_products" => "on"
     *      "development_public_api_key" => "key"
     *      "development_private_api_key" => "key"
     *      "type" => "production"
     *      "production_public_api_key" => "key"
     *      "production_private_api_key" => "key"
     *      ]
     *      "paypal" => [
     *      "type" => "development"
     *      "development_public_api_key" => "key"
     *      "development_private_api_key" => "key"
     *      "production_public_api_key" => "key"
     *      "production_private_api_key" => "key"
     *      ]
     *
     * @return array|mixed
     */
    static public function createOrUpdatePaymentSystems($brandId, $pymentSources)
    {
        $brandPayments = self::brandPaymentSystems($brandId);
        $paymentSystems = self::paymentSystems();
        $promises = [];
//        dd($pymentSources);
        $token = self::adminLogin();

        foreach ($pymentSources as $key => $method) {
            if (!isset($paymentSystems[ $key ])) {
                continue;
            }
            $options = [
                'form_params' => [
                    'env' => (($method['type'] ?? 'dev') === 'production') ? 'prod' : 'dev',
//
//                    Api_key = production public api key
//                    Apisecretkey = production private key
//                    Apikeydevmode = development public key
//                    Apisecretkey dev mode = development private key

                    'apikey'               => $method['production_public_api_key'],
                    'apisecretkey'         => $method['production_private_api_key'],
                    'apikey_devmode'       => $method['development_public_api_key'],
                    'apisecretkey_devmode' => $method['development_private_api_key'],

                    'publickey'         => (isset($method['production_publishable_key'])) ? $method['production_publishable_key'] : null,
                    'publickey_devmode' => (isset($method['development_publishable_key'])) ? $method['development_publishable_key'] : null,
                    'webhook'           => config('app.url') . '/webhook/gafa-pay',
                    'hasshipping'       => (isset($method['only_virtual_products']) && $method['only_virtual_products'] == 'on') ? 1 : 0,
                ],
                'headers'     => [
                    'Content-Type'  => 'application/x-www-form-urlencoded',
                    'Authorization' => "Bearer $token",
                ],
            ];


            if (in_array($key, array_keys($brandPayments))) {
                $method = 'PUT';
                $url = "brands/$brandId/paymentsystems/{$brandPayments[$key]}";
            } else {
                $method = 'POST';
                $url = "brands/$brandId/paymentsystems/{$paymentSystems[$key]}";
            }

            $onSuccess = function ($res) {
//                dd($res);
            };

            $onErr = function ($res) use ($brandId, $method) {
//                dd($res);
                Log::error("paymentsystems $method brand[$brandId] gafapay. [$res]");
            };

            $promises[ $key ] = self::requestAsync(
                $url, $onSuccess, $onErr, $method, $options
            );
        }


        $onDeletedSuccess = function ($res) {

        };

        $onDeletedErr = function ($res) use ($brandId) {
//                dd($res);
            Log::error("paymentsystems DELETE brand[$brandId] gafapay. [$res]");
        };

        try {
            $results = Promise\settle($promises)->wait();

//            dd($results);
            return $results;
        } catch (\Exception $e) {
            Log::error("paymentsystems brand[$brandId] gafapay. [$e]");

            return [];
        }

    }

    /**
     * @param string $email
     * @param string $paymentSourceSlug
     *
     * @return array|mixed
     */
    static public function getPaymentSourcesByEmail($clientId, $clientSecret, $email = '__', $paymentSourceSlug = '__')
    {
        $method = 'POST';
        $url = "{$paymentSourceSlug}.client.paymentsources.byemail";
        $options = [
            'form_params' => [
                'email' => $email,
            ],
        ];

        $response = self::requestWithAuthBrand(
            $clientId,
            $clientSecret,
            $url,
            $method,
            $options
        );

        return $response ?: [];
    }

    /**
     * @param $clientId
     * @param $clientSecret
     * @param $options
     *
     * @return array
     */
    static public function checkPaymentInGafaPay($clientId, $clientSecret, $options)
    {
        $method = 'POST';
        $url = "check-payment";
        $options = [
            'form_params' => $options,
        ];

        $options['headers'] = ['Authorization' => "Bearer " . self::adminLogin()];


        $response = self::request(
            $url,
            $method,
            $options
        );

        return $response ?: [];
    }

    /**
     * @param $subscription
     * @param $options
     *
     * @return array
     */
    static public function updateMembership($subscription, $options)
    {
        $method = 'POST';
        $url = "subscription.update/{$subscription}";
        $options = [
            'form_params' => $options,
        ];
        $options['headers'] = ['Authorization' => "Bearer " . self::adminLogin()];


        $response = self::request(
            $url,
            $method,
            $options
        );

        return $response ?: null;
    }


    /**
     * @param        $clientId
     * @param        $clientSecret
     * @param string $option
     * @param string $email
     * @param string $paymentSourceSlug
     *
     * @return array|mixed
     */
    static public function addPaymentSourcesByEmail(
        $clientId,
        $clientSecret,
        $option,
        $email = '__',
        $paymentSourceSlug = '__'
    )
    {
        $method = 'POST';
        $url = "{$paymentSourceSlug}.client.addpaymentsources.byemail";
        $options = [
            'form_params' => [
                'email'  => $email,
                'option' => $option,
            ],
        ];

        $response = self::requestWithAuthBrand(
            $clientId,
            $clientSecret,
            $url,
            $method,
            $options
        );

        return $response ?: [];
    }

    static public function removePaymentSourcesByEmail(
        $clientId,
        $clientSecret,
        $option,
        $email = '__',
        $paymentSourceSlug = '__'
    )
    {
        $method = 'POST';
        $url = "{$paymentSourceSlug}.client.deletepaymentsources.byemail";
        $options = [
            'form_params' => [
                'email'  => $email,
                'option' => $option,
            ],
        ];
        $response = self::requestWithAuthBrand(
            $clientId,
            $clientSecret,
            $url,
            $method,
            $options
        );

        return $response ?: [];
    }
}
