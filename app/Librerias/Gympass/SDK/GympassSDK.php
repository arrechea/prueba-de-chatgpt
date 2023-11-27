<?php

namespace App\Librerias\Gympass\SDK;

use App\Models\Company\Company;
use App\Models\Location\Location;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Config\Repository;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Log;

abstract class GympassSDK
{
    /**
     * @string
     */
    protected $url_key;
    /**
     * @string
     */
    protected $url_dev_key;

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @int
     */
    private $code;

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @param string $error
     *
     * @return void
     */
    protected function addError(string $error)
    {
        $errors = $this->getErrors();
        $errors[] = $error;
        $this->errors = $errors;
    }

    /**
     * @param Location $location
     *
     * @return Repository|Application|mixed
     */
    protected function getBaseURI(Location $location)
    {
        return $location->isGympassProduction() ? config($this->url_key) : config($this->url_dev_key);
    }

    /**
     * @param Location $location
     *
     * @return Repository|Application|mixed
     */
    protected function getAuthToken(Location $location)
    {
        return $location->isGympassProduction() ?
//            config('gympass.auth_token') :
            'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJjMGYwYzRiYi1kOGRjLTRkMjYtODI3ZS1jMjY4MWYzMWRjYTYiLCJpYXQiOjE2OTY1MTM0NzAsImlzcyI6ImlhbS51cy5neW1wYXNzLmNsb3VkIiwic3ViIjoiYzBmMGM0YmItZDhkYy00ZDI2LTgyN2UtYzI2ODFmMzFkY2E2In0.ppFXjG88iPBpP7SrQAQAOLu9w9vvH6jZDFFSsYdIGh0' :
            config('gympass.auth_dev_token');
    }

    /**
     * @param Location $location
     * @param string   $endPoint
     * @param string   $method
     * @param array    $options
     * @param array    $body
     *
     * @return mixed|null
     */
    protected function request(
        Location $location,
        string   $endPoint,
        string   $method = 'GET',
        array    $options = [],
        array    $body = []
    )
    {
        if ($location->isGympassActive()) {
            $uri = $this->getBaseURI($location);
            $uri_object = new Uri($uri . $endPoint);
            Log::info($uri . $endPoint);
            Log::info(json_encode($body));
            Log::info($method);
//            $host = $uri_object->getHost();
//            $path = $uri_object->getPath();
            $client = new Client([
//                'base_uri'    => $host,
                'http_errors' => false,
            ]);
            if (!isset($options['headers'])) {
                $options['headers'] = [
                    'Content-Type' => 'application/json',
                ];
            } else if (!isset($options['headers']['Content-Type'])) {
                $options['headers']['Content-Type'] = 'application/json';
            }
//            if($method==='PUT')dd($options,$uri_object,$body);
            $request = count($body) > 0 ? new Request($method, $uri_object, $options['headers'], \GuzzleHttp\json_encode($body)) : new Request($method, $uri_object, $options['headers']);
            $response = $client->sendAsync($request, $options)->wait();
            $statusCode = $response->getStatusCode();
            \Log::info($statusCode);
            $this->setCode($statusCode);
            if ($statusCode == 500) {
                Log::error(__('gympass.internalError'));
                $this->addError(__('gympass.internalError'));
                Log::error($response->getBody()->getContents());

                return null;
            }

            $data = json_decode($response->getBody()->getContents());
            \Log::info(json_encode($data));
            if ($statusCode >= 200 && $statusCode < 300) {
                Log::info("Respuesta exitosa para el endpoint $endPoint con método $method");

                return $data;
            }

            if ($statusCode >= 400 && $statusCode < 500) {

                $error = null;
                if (is_object($data)) {
                    $error = isset($data->message) ? $data->message : (isset($data->Message) ? $data->Message : (isset($data->errors) ? $data->errors : null));
                }

                \Log::info(json_encode($data));

                if ($error) {
                    if (is_array($error)) {
                        $msg = '';
                        foreach ($error as $k => $err) {
                            $msg .= (is_string($err) ? $err : (is_object($err) && isset($err->message) ? $err->message : __('gympass.unknownError')));
                            if (is_object($err) && isset($err->key) && in_array($err->key, ['checkin.validation.notfound', 'checkin.validation.canceled', 'checkin.validation.expired'])) {
                                $msg .= '. ' . __('gympass.checkinRetryInGympass');
                            }
                        }
                        $error = $msg;
                    }
                } else {
                    if (is_array($data) && count($data) > 0 && $data[0]->error) {
                        foreach ($data as $err) {
                            Log::error(__('gympass.gympassError', ['err' => $err->error]));
                            $this->addError(__('gympass.gympassError', ['err' => $err->error]));
                        }
                        $error = null;
                    } else if (is_object($data) && $data->errors) {
                        $errors = $data->errors;
                        if (is_array($errors)) {
                            foreach ($errors as $e) {
                                $message = $e->key ?? 'error' . ': ';
                                $message .= $message . $e->message ?? __('gympass.unknownError');
                                $this->addError($message);
                            }
                        }
                        $error = null;
                    } else {
                        $error = __('gympass.unknownError');
                    }
                }

                if ($error) {
                    Log::error(__('gympass.gympassError', ['err' => $error]));
                    Log::error(json_encode(['endpoint' => $endPoint, 'body' => $body, 'options' => $options]));
                    $this->addError(__('gympass.gympassError', ['err' => $error]));
                }
            }
        }

        return null;
    }


    /**
     * @param Location $location
     * @param string   $endPoint
     * @param string   $method
     * @param array    $options
     *
     * @return mixed|null
     */
    protected function requestWithAuth(Location $location, string $endPoint, string $method = 'GET', array $options = [], array $body = [])
    {
        if ($location->isGympassActive()) {
            if (!isset($options['headers'])) {
                $options['headers'] = ['Authorization' => "Bearer " . $this->getAuthToken($location)];
            } else {
                if (!isset($options['headers']['Authorization'])) {
                    $options['headers']['Authorization'] = 'Bearer ' . $this->getAuthToken($location);
                }
            }

            return $this->request($location, $endPoint, $method, $options, $body);
        }

        return null;
    }

    /**
     * @param Location $location
     * @param string   $endPoint
     * @param int|null $gym_id
     * @param string   $method
     * @param array    $options
     * @param array    $body
     *
     * @return mixed|null
     */
    protected function requestWithGym(Location $location,
                                      string   $endPoint,
                                      int      $gym_id = null,
                                      string   $method = 'GET',
                                      array    $options = [],
                                      array    $body = [])
    {
        $endPoint = $gym_id !== null ? "gyms/$gym_id/$endPoint" : $endPoint;

        return $this->requestWithAuth($location, $endPoint, $method, $options, $body);
    }

    /**
     * @param Location $location
     * @param string   $endPoint
     * @param int|null $gym_id
     * @param array    $data
     * @param string   $method
     *
     * @return mixed|null
     */
    protected function postFormData(Location $location, string $endPoint, int $gym_id = null, array $data = [], string $method = 'POST')
    {
//        $options = [
//            'json' => $data,
//        ];

        return $this->requestWithGym($location, $endPoint, $gym_id, $method, [], $data);
    }

}
