<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 21/01/2019
 * Time: 17:01
 */

namespace App\Librerias\MailchimpSDK;


use App\Models\Company\Company;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Facades\Log;

class LibMailchimpSDK
{
    private $url;
    private $headers;
    private $list_id;
    private $apikey;
    private $identifyer_user = '<dc>';
    private $company;

    /**
     * Asegurarse que la compañía tenga un id de lista y un
     * api key de mailchimp (se pueden modificar en ajustes
     * o edición de compañía) y que el .env tenga la Url
     * del API de Mailchimp correcta
     *
     * LibMailchimpSDK constructor.
     *
     * @param Company $company
     */
    public function __construct(Company $company)
    {
        $this->url = env('MAILCHIMP_URL');
        $this->list_id = $company->mailchimp_list_id;
        $this->apikey = $company->mailchimp_apikey;
        $this->headers = [
            'Authorization' => "apikey $company->mailchimp_apikey",
        ];
        $this->company = $company;
    }

    /**
     * Subscribe un email a la lista asignada a la compañía.
     *
     * @param string $email
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function subscribe(string $email)
    {
        return $this->updateMember($email, [
            'email_address' => $email,
            'status'        => 'subscribed',
        ]);
    }

    /**
     * Desubscribe un email de la lista asignada a la compañía.
     *
     * @param string $email
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function unsubscribe(string $email)
    {
        return $this->updateMember($email, [
            'email_address' => $email,
            'status'        => 'unsubscribed',
        ]);
    }

    /**
     * Actualiza el email de un miembro de la lista.
     * Hay que enviar el email actual (en la lista) y el nuevo
     * email al que se quiera pasar.
     *
     * @param string $currentEmail
     * @param string $newEmail
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function updateEmail(string $currentEmail, string $newEmail)
    {
        return $this->updateMember($currentEmail, [
            'email_address' => $newEmail,
        ]);
    }

    /**
     * Función para actualizar o crear un miembro en la lista.
     * Se le pasa el email que se está usando/se va a usar en la
     * lista y un arreglo con las opciones a insertar.
     * Documentación completa de opciones:
     * https://developer.mailchimp.com/documentation/mailchimp/reference/lists/members/
     *
     * @param string $email
     * @param array  $options
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function updateMember(string $email, array $options)
    {
        $encoded_email = md5($email);
        $path = "lists/$this->list_id/members/$encoded_email";

        $data = $options;

        return $this->sendRequest('PUT', $path, $data);
    }

    /**
     * Borra un miembro de la lista
     *
     * @param string $email
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteMember(string $email)
    {
        $encoded_email = md5($email);
        $path = "lists/$this->list_id/members/$encoded_email";

        return $this->sendRequest('DELETE', $path);
    }

    /**
     * Obtiene la información de un miembro de la lista
     *
     * @param string $email
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getMember(string $email)
    {
        $encoded_email = md5($email);
        $path = "lists/$this->list_id/members/$encoded_email";

        return $this->sendRequest('GET', $path);
    }

    /**
     * Obtiene el data center definido en el apikey y lo inyecta en la url.
     *
     * @return string
     */
    private function getRequestUrlByApiKey(): string
    {
        $urlBase = $this->url;
        $apiKey = $this->apikey;

        $explode = explode('-', $apiKey);
        $subdomain = end($explode);
        if ($subdomain) {
            return str_replace($this->identifyer_user, $subdomain, $urlBase);
        }

        return '';
    }

    /**
     * Función que envía la petición al API de Mailchimp. Si no hay id de lista o apikey,
     * se regresa un arreglo vacío.
     *
     * @param string $method
     * @param string $path
     * @param array  $data
     *
     * @return array|mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function sendRequest(string $method = 'GET', string $path, array $data = [])
    {
        try {
            if (!$this->url || !$this->apikey || !$this->list_id) {
                return [];
            }

            $headers = $this->headers;

            $client = new Client([
                'base_uri' => $this->getRequestUrlByApiKey(),
                'headers'  => $headers,
            ]);

            $response = $client->request($method, $path, [
                'json' => $data,
            ]);

            return json_decode((string)$response->getBody(), true);
        } catch (ConnectException $e) {
            Log::error($e, $data, $path);

            return [];
        } catch (ClientException $e) {
            if ($e->getCode() === 404) {
                Log::error($e, $data, $path);

                return [];
            }
        }
    }
}
