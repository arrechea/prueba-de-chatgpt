<?php
/**
 * Created by Neos Control.
 * User: Neos
 * Date: 2020-08-11
 * Time: 14:53
 */

namespace App\Librerias\Webhooks;
use App\Models\Company\Company;
use App\Models\User\UserProfile;
use App\User;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;


class LibClientSites
{

    var $SERVICENAME = 'LibClientSites Sync Webhooks';
    var $error = false;
    var $error_msj = '';
    var $errors = null;       // Lista de los errores encontrados
    private $company = null;
    private $webhook = null;


    public static function getWebhook(Company $company)
    {
        //self::resetError();
        if(empty($company)){
          Log::error("LibClientSites Error: (getWebhook) No company set");
          return null;
        }

        //self::company = $company;

        // verify company's webhook
        if((empty($company->webhooks)) || (count($company->webhooks)<=0)){
            //self::setError("LibClientSites Sync Webhooks: (setCompany) No webhook defined for this company (".self::company->name.")");
            Log::error("LibClientSites Error:: (getWebhook) No webhook defined for this company ({$company->name})");
            return null;
        }

        $t_webhook = $company->webhooks->first();
        Log::debug("LibClientSites: (getWebhook) webhook defined: {$t_webhook->webhook}");
        return $t_webhook->webhook;
    }


    public static function request($method = 'GET', $url,  $options = [])
    {

        Log::debug("LibClientSites: (request) ------->using url: {$url}");
        //self::resetError();
        if(empty($url)){
            //self::setError("LibClientSites Sync Webhooks: (request) No webhook defined");
            Log::error("LibClientSites Error: (request) No webhook defined");
            return null;
        }

        $client = new Client(['base_uri' => $url, 'http_errors' => false]);
        //$client = new Client(['base_uri' => $url]);
        Log::debug("LibClientSites: (request) - sendig: ".json_encode($options));
        $response = $client->request($method, $url, $options);
        Log::debug("LibClientSites: (request) - response: ".json_encode($response));
        $statusCode = $response->getStatusCode();
        Log::debug("LibClientSites: (request) - Status Code: ".$statusCode);
        if ($statusCode == 500) {
            //self::setError("LibClientSites Sync Webhooks: (request) internal error [500]");
            Log::error("LibClientSites Error: (request) internal error [500]");
            return null;
        }

        $data = json_decode($response->getBody()->getContents());
        Log::debug("LibClientSites: (request) - response body: ".json_encode($data));
        if ($statusCode == 200 || $statusCode == 201) {
            return $data;
        }
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

            //self::setError("LibClientSites Sync Webhooks: (request) error {$error}");
            Log::error("LibClientSites Error: (request) - {$error}");
        }

        return null;
    }


    /**
     * Verify remote client's site availability
     *
     * @return bool
     */
    public static function pingRemote(Company $company)
    {
        Log::debug("LibClientSites: (pingRemote) ------->");

        if(empty($company)){
          Log::error("LibClientSites Error: (pingRemote) No company defined");
          return false;
        }

        $webhook = self::getWebhook($company);
        if(empty($webhook)){
          return false;
        }

        $client = $company->client;
        $client_id = $client->id;
        $client_secret = $client->secret;

        $data = [
                  'form_params' => [
                        'type'          => 'permission',
                        'auth_id'       => $client_id,
                        'auth_secret'   => $client_secret
                    ]
                ];

        // ping client's remote site
        $response = self::request('POST', $webhook, $data);
        Log::debug("LibClientSites: (pingRemote) Ping a sitio compañia");
        Log::debug(json_encode($response));
        if(empty($response)){
          Log::error("LibClientSites Error: (pingRemote) Remote client site not responding");
          return false;
        }

        return true;
    }


    /**
     * Synchronize new users with remote customer site
     *
     * @param UserProfile
     * @param array   (user values override)
     *
     * @return bool
     */
    public static function addUser(UserProfile $userprofile, $options = [])
    {

        Log::debug("LibClientSites: (addUser) ------->");

        if(empty($userprofile->company)){
          Log::error("LibClientSites Error: (addUser) No company defined");
          return false;
        }

        $company = $userprofile->company;

        if(!self::pingRemote($company)){
          return false;
        }

        $webhook = self::getWebhook($company);

        $client = $company->client;
        $client_id = $client->id;
        $client_secret = $client->secret;


        // new user info
        $auth_data = [
                        'type'          => 'adduser',
                        'auth_id'       => $client_id,
                        'auth_secret'   => $client_secret
                    ];
        $user_data_1 = [
              'email'             => $userprofile->email,
              'password'          => $userprofile->password,
              'first_name'        => $userprofile->first_name,
              'last_name'         => $userprofile->last_name,
              'birth_date'        => $userprofile->birth_date,
              'address'           => $userprofile->address,
              'external_number'   => $userprofile->external_number,
              'internal_number'   => $userprofile->internal_number,
              'postal_code'       => $userprofile->postal_code,
              'municipality'      => $userprofile->municipality,
              'city'              => $userprofile->city,
              'gender'            => $userprofile->gender,
              'phone'             => $userprofile->phone,
              'cel_phone'         => $userprofile->cel_phone
            ];

        // Set override values
        $user_data = array_merge($auth_data, $user_data_1, $options);

        $data = [
          'form_params' => $user_data
        ];

        // Sync new user
        $response = self::request('POST', $webhook, $data);
        Log::debug("LibClientSites: (addUser) Send user data");
        Log::debug(json_encode($response));
        if(empty($response)){
          //self::setError("(addUser) User not synced");
          Log::error("LibClientSites Error: (addUser) User not synced");
          return false;
        }

        return true;
    }


    /**
     * Synchronize updated users with remote customer site
     *
     * @param UserProfile
     * @param array   (user values override)
     *
     * @return bool
     */
     public static function updateUser(UserProfile $userprofile, $options = [])
     {

         Log::debug("LibClientSites: (updateUser)------->");

         if(empty($userprofile->company)){
           Log::error("LibClientSites Error: (updateUser) No company defined");
           return false;
         }

         $company = $userprofile->company;

         if(!self::pingRemote($company)){
           return false;
         }

         $webhook = self::getWebhook($company);

         $client = $company->client;
         $client_id = $client->id;
         $client_secret = $client->secret;


         $old_email = $userprofile->email;
         $new_email = $userprofile->email;
         if(isset($options['email'])){
           $new_email = $options['email'];
         }

         // update user info
         $auth_data = [
                         'type'          => 'updateuser',
                         'auth_id'       => $client_id,
                         'auth_secret'   => $client_secret
                     ];
         $user_data_1 = [
               'email'             => $userprofile->email,
               'password'          => $userprofile->password,
               'first_name'        => $userprofile->first_name,
               'last_name'         => $userprofile->last_name,
               'birth_date'        => $userprofile->birth_date,
               'address'           => $userprofile->address,
               'external_number'   => $userprofile->external_number,
               'internal_number'   => $userprofile->internal_number,
               'postal_code'       => $userprofile->postal_code,
               'municipality'      => $userprofile->municipality,
               'city'              => $userprofile->city,
               'gender'            => $userprofile->gender,
               'phone'             => $userprofile->phone,
               'cel_phone'         => $userprofile->cel_phone
             ];

         // Set override values
         $user_data = array_merge($auth_data, $user_data_1, $options);


         if($old_email != $new_email){
           $user_data['email'] = $old_email;
           $user_data['newemail'] = $new_email;
         }

         $data = [
           'form_params' => $user_data
         ];

         // Sync new user
         $response = self::request('POST', $webhook, $data);
         Log::debug("LibClientSites: (updateUser) Send user data");
         Log::debug(json_encode($response));
         if(empty($response)){
           //self::setError("(addUser) User not synced");
           Log::error("LibClientSites Error: (updateUser) User not synced");
           return false;
         }

         return true;
     }


}
