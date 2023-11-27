<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 19/04/2018
 * Time: 09:34 AM
 */

namespace App\Librerias\SDKs;


use App\Librerias\Settings\LibSettings;
use App\Models\gafafit\Settings;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;

class LibCloudflareSDK
{
    private $settings = [];
    private $client;
    private $uri = '';
    private $uri_records = '';
    private $headers = [];
    private $enabled = false;

    private $required = [
        'cloudflare.zone-id',
        'cloudflare.x-auth-key',
        'cloudflare.x-auth-email',
        'cloudflare.ip',
        'cloudflare.base-uri',
    ];

    public function __construct()
    {
        $this->settings = new LibSettings();
        $this->enabled = $this->settings->get('cloudflare.enabled') !== '' ? true : false;
        $this->uri = 'zones/' . $this->settings->get('cloudflare.zone-id');
        $this->uri_records = $this->uri . '/dns_records';
        $this->headers = [
            'Content-Type' => 'application/json',
            'X-Auth-Key'   => $this->settings->get('cloudflare.x-auth-key'),
            'X-Auth-Email' => $this->settings->get('cloudflare.x-auth-email'),
        ];
    }

    /**
     * Crea nuevo sitio en cloudflare.
     * Primero revisa si ya existe el sitio. Si ya existe, regresa ese sitio. Si no
     * existe lo crea y devuelve éste.
     * Llama a la función 'isActive' para verificar que los 'settings' adecuados
     * estén presentes. Si no, regresa un array vacío.
     *
     * @param string $slug
     *
     * @return array|mixed
     */
    public function newSite(string $slug)
    {
        if (!$this->isActive())
            return [];

        $site = $this->searchSite($slug);
        if (!$site) {
            $site = $this->postSite($slug);
        }

        return $site;
    }

    /**
     * Elimina un sitio DNS de cloudflare. Primero busca el sitio utilizando la
     * función 'searchSite'. Si no se encuentra, regresa un array vacío.
     * Después usa la función 'deleteDNS' usando el 'id' obtenido en la
     * búsqueda para borrar el sitio.
     * Llama a la función 'isActive' para verificar que los 'settings' adecuados
     * estén presentes. Si no, regresa un array vacío.
     *
     * @param string $slug
     *
     * @return array|mixed
     */
    public function deleteSite(string $slug)
    {
        if (!$this->isActive())
            return [];

        $site = $this->searchSite($slug);
        if (!$site) {
            return [];
        }

        return $this->deleteDNS($site);
    }

    /**
     * Inserta un nuevo registro DNS dentro de la zona gafafit.
     * Por default, inserta el tipo 'A' con el nombre '{slug}.{url-gafafit}'.
     * El campo 'content' es el ip que se va a insertar.
     *
     * @param string $slug
     *
     * @return array|mixed
     */
    private function postSite(string $slug)
    {
        $json = [
            'type'    => 'A',
            'name'    => $slug,
            'content' => $this->settings->get('cloudflare.ip'),
            'proxied' => true
        ];

        return $this->sendRequest('POST', $this->uri_records, $json);
    }

    /**
     * Función que envía la petición de eliminación del DNS en cuestión.
     * Primero comprueba que esté seteado el 'id' del sitio y luego
     * remueve la cabecera 'Content-Type'.
     * Para utilizar el endpoint en cloudflare, se le agrega a la 'uri_records'
     * el 'id' del DNS en Cloudflare para indicar cual se debe de borrar.
     *
     * @param array $site
     *
     * @return array|mixed
     */
    private function deleteDNS(array $site)
    {
        if ($site['id']) {
            $headers = $this->headers;
            unset($headers['Content-Type']);

            return $this->sendRequest('DELETE', $this->uri_records . '/' . $site['id'], [], [], $headers);
        } else
            return [];
    }

    /**
     * Busca en Cloudflare la url con el slug de la compañía.
     * Para la búsqueda se utiliza el string '{slug}.{url_gafafit}'
     * Regresa el primer resultado solamente.
     *
     * @param $slug
     *
     * @return mixed
     */
    private function searchSite(string $slug)
    {
        $url = $this->getUrl();

        $response = $this->sendRequest('GET', $this->uri_records, [], [
            'name' => $slug . '.' . $url,
        ]);

        return reset($response['result']);
    }

    /**
     * Obtiene la url de la zona gafafit utilizando el id
     * de la zona
     *
     * @return string
     */
    private function getUrl()
    {
        $url = $this->settings->get('cloudflare.url');
        if ($url === '') {
            $url = $this->getZoneName();
            Settings::updateOrCreate([
                'meta_key' => 'cloudflare.url',
            ], [
                'meta_value' => $url,
            ]);
        }

        return $url;
    }

    /**
     * Envía la petición para obtener la información usando el id de
     * la zona. Regresa únicamente el nombre (url)
     *
     * @return string
     */
    private function getZoneName()
    {
        $result = $this->sendRequest('GET', $this->uri);

        return $result['result'] ? $result['result']['name'] : '';
    }

    /**
     * Verifica que todos los 'settings' necesarios para hacer
     * las peticiones a cloudflare
     * estén dados de alta, si no regresa un falso.
     *
     * @return bool
     */
    private function isActive()
    {
        if (!$this->enabled)
            return false;

        foreach ($this->required as $key) {
            if ($this->settings->get($key) === '')
                return false;
        }

        return true;
    }

    /**
     * Función que envía una petición a Cloudflare. Se le pasa el método,
     * los parámetros de búsqueda y el cuerpo de la petición y regresa
     * el cuerpo de la respuesta como un array. Si no se puede conectar,
     * se envía un array vacío.
     *
     * @param string $method
     * @param        $path
     * @param array  $json
     * @param array  $query
     *
     * @return array|mixed
     */
    private function sendRequest(string $method = 'GET', string $path, array $json = [], array $query = [], array $headers = [])
    {
        try {
            $headers = !$headers ? $this->headers : $headers;

            $client = new Client([
                'base_uri' => $this->settings->get('cloudflare.base-uri'),
                'headers'  => $headers,
            ]);

            if (!$query) {
                $response = $client->request($method, $path, [
                    'json' => $json,
                ]);
            } else {
                $response = $client->request($method, $path, [
                    'query' => $query,
                ]);
            }

            return json_decode((string)$response->getBody(), true);
        } catch (ConnectException $e) {
            Log::error($e, $json, $path);

            return [];
        }
    }
}
