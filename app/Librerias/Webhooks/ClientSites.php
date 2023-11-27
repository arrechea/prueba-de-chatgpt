<?php

namespace App\Librerias\Webhooks;

use Illuminate\Http\Request;

//use App\Models\Purchase;
//use App\Models\User\UsersMemberships;
//use App\Models\Membership\Membership;
//use App\Models\Brand\Brand;
//use App\Models\Purchase\Purchase;
use App\Models\AuthClient\AuthClient;
use App\Models\Company\Company;
use App\Models\User\UserProfile;
use App\Librerias\Models\Users\LibUsers;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Company\CatalogUserProfile;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Validator;
use Illuminate\Validation\Rule;

/**
 *  Procesamiento de Webhooks para sincronización de usuarios con Sitios del Cliente (company)
 *
 *  Implementaciones del lado del cliente:
 *    BuqWordpressPlugin
 */
class ClientSites
{
    var $SERVICENAME = 'ClientSites Sync Webhooks';
    var $error = false;
    var $error_msj = '';
    var $errors = null;       // Lista de los errores encontrados
    var $company = null;          //  $company->client->id, $company->client->secret
    var $auth_id = null;       //  $company->client->id
    var $auth_secret = null;   //  $company->client->secret
    var $userprofile = null;      //  $user->company, $userprofile->user


    /**
     * Clear Error Variables
     *
     * @return array|void
     */
    public function resetError()
    {
        $this->error = false;
        $this->error_msj = '';
        $this->errors = null;
    }

    /**
     * Set Error Variables
     *
     * @return array|void
     */
    public function setError($error,$errors=null)
    {
        $this->error = true;
        $this->error_msj = $error;
        $this->errors = $errors;
        Log::error("{$this->SERVICENAME} Error: {$error}");
        if(!empty($errors)){
            Log::error("{$this->SERVICENAME} Errors: ".json_encode($errors));
        }
    }


    /**
     * Validate request using given rules array
     *
     * @param  Request  $request
     * @param  array  $rule
     * @return Response
     */
    public function validate(Request $request,$rules = [], $messages = [])
    {

        $this->resetError();

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $this->setError('Existen errores de validación.', $validator->errors());
            return false;
        }

        return true;
    }

    /**
     * Entrypoint: Process Webhooks requests
     *
     * @return array|void
     */
    public function process(Request $request)
    {

        $this->authrequest($request);
        if ($this->error) {
            return ['status' => 'error', 'message' => $this->error_msj, 'errors'=>$this->errors,'code' => 403];
        }

        $type = ($request->has('type') ? $request['type'] : '');

        switch ($type) {
            case 'permission':  // Remote Client Very GafaFit Permissions
                return $this->ping($request);
                break;
            case 'verifyuser':     // Remote Cliente Request Add New User
                return $this->verifyUser($request);
                break;
            case 'adduser':     // Remote Cliente Request Add New User
                return $this->addUser($request);
                break;
            case 'updateuser':  // Remote Cliente Request Update User
                return $this->updateUser($request);
                break;
            case 'setpassword': // Remote Cliente Request Set User's Password
                return $this->setPassword($request);
                break;
            case 'deleteuser':  // Remote Cliente Request Delete User
                return $this->deleteUser($request);
                break;
            case 'restoreuser': // Remote Cliente Request Restore Deleted User
                return $this->restoreUser($request);
                break;
            case 'setremotewebhook':  // Remote Cliente Request Set It's Remote Webhook To Receive Notifications
                return $this->setRemoteWebhook($request);
                break;
            default:
                return ['status' => 'error', 'message' => __('client-sites-sync.service_undefined'), 'errors'=>null, 'code' => 403];
                break;
        }
    }


    /**
     * Validate webhook request credentials.
     *
     * @return void
     */
    private function authrequest(Request $request)
    {

        // Validaciones comunes basicas
        $ok = $this->validate($request,
            [
                'type'=>'required|string|max:50',
                'auth_id'=>'required|numeric|exists:oauth_clients,id',
                'auth_secret'=>'required|string|max:50',
                'tokenmovil'=>"required",
            ]);

        if(!$ok){
            return false;
        }

        // Verify tokenmovil
        if($request->tokenmovil !== config('app.hard_coded_mobile_token')){
            $this->setError( "(authrequest) - ".__('client-sites-sync.invalid_mobile_token'));
            return false;
        }


        $type = $request['type'];
        $auth_id = $request['auth_id'];
        $auth_secret = $request['auth_secret'];

        // validate id
        $auth_client = AuthClient::find($auth_id);
        if(empty($auth_client)){
            $this->setError("(authrequest) - ".__('client-sites-sync.invalid_auth_id'));
            return false;
        }

        // validate client secret
        $valid_secret = ($auth_secret == $auth_client->secret);
        if (!$valid_secret) {
            $this->setError("(authrequest) - ".__('client-sites-sync.invalid_auth_secret'));
            return false;
        }

        // Verify Client Revoked
        if($auth_client->revoked){
            $this->setError("(authrequest) - ".__('client-sites-sync.auth_revoked'));
            return false;
        }

        // Set current company
        $company = Company::find($auth_client->companies_id);
        if(empty($company)){
            $this->setError("(authrequest) - ".__('client-sites-sync.company_not_found'));
            return false;
        }
        $this->company = $company;
        $this->auth_id = $auth_id;
        $this->auth_secret = $auth_secret;

        // Verify Company status
        if($this->company->status<>'active'){
            $this->setError("(authrequest) - ".__('client-sites-sync.company_inactive'));
            return false;
        }

        return true;
    }


    /**
     * Webservice echo response and auth verification
     *
     * @return array
     */
    private function ping(Request $request)
    {

        return ['status' => 'ok', 'message' => 'ok', 'code' => 200, 'data' => ['company' => $this->company->name]];
    }


    /**
     * Verify if user exists in Buq
     *
     *  $data = [
     *            'email',      //  varchar(191)  #
     *          ]
     *
     *  # Mandatory
     *
     * @return array
     */
    private function verifyUser(Request $request)
    {

        $this->resetError();

        $ok = $this->validate($request, [
                      'email'              => [
                          'required',
                          'email',
                      ],
                  ],
                  [
                      'email.email'    => __('client-sites-sync.email-format'),
                      'email.required' => __('client-sites-sync.email-missing'),
                  ]);


        if(!$ok){
          Log::error("{$this->SERVICENAME} Error: (verifyUser). message: {$this->error_msj}, errors: ".json_encode($this->errors));
          return ['status' => 'error', 'message' => $this->error_msj, 'errors'=>$this->errors,'code' => 403];
        }

        // Localizar usuario previo
        $profile = UserProfile::where(
            [
                ['companies_id', $this->company->id],
                ['email', $request->get('email')],
            ]
        )->withTrashed()->first();

        // El usuario ya existe en BUQ, actualizar sus datos en base al sistema remoto
        if ($profile) {
          Log::debug("{$this->SERVICENAME}: (verifyUser). message: El usuario '".$request->get('email')."' existe");

          $response =  [
                    'status'     => 'ok',
                    'message'    => 'El usuario existe',
                    'email'      => $profile->email,
                    'first_name' => $profile->first_name,
                    'last_name'  => $profile->last_name,
                    'code'       => 200
                  ];


          // Sync passwords
          if($request->has('password') && (!empty($request->get('password')))){
            Log::debug("{$this->SERVICENAME}: (verifyUser). Password set in request:  '".$request->get('password')."'");
            if (!Hash::check($request->get('password'), $profile->password)) {
              $profile->password = $request->get('password');
              $profile->save();
              Log::debug("{$this->SERVICENAME}: (verifyUser). Password updated");
            }else{
              Log::debug("{$this->SERVICENAME}: (verifyUser). Password match, no action");
            }
          }

          // verify password
          if($request->has('verifypassword') && (!empty($request->get('verifypassword')))){
            Log::debug("{$this->SERVICENAME}: (verifyUser). Verify Password:  '".$request->get('verifypassword')."'");
            if (Hash::check($request->get('verifypassword'), $profile->password)) {
              Log::debug("{$this->SERVICENAME}: (verifyUser). Password match");
              $response['verifypassword'] = true;
            }else{
              Log::debug("{$this->SERVICENAME}: (verifyUser). Password wrong");
              $response['verifypassword'] = false;
            }
          }

          return $response;
        }else{
          Log::debug("{$this->SERVICENAME} Error: (verifyUser). message: El usuario '".$request->get('email')."' NO existe");
          return ['status' => 'error', 'message' => 'El usuario NO existe', 'code' => 403];
        }
    }



    /**
     * Add local new user from client site request
     *
     *  $data = [
     *            'email',      //  varchar(191)  #
     *            'password',   //  varchar(191)  #
     *            'password_confirmation',   //  varchar(191)  #
     *            'first_name', //  varchar(191)
     *            'last_name',  //  varchar(191)
     *            'birth_date', //  datetime
     *            'address',    //  varchar(191)
     *            'external_number',  //  varchar(191)
     *            'internal_number',  //  varchar(191)
     *            'postal_code',      //  varchar(191)
     *            'municipality',     //  varchar(191)
     *            'city',       //  varchar(191)
     *            'gender',     //  enum('male','female')
     *            'phone',      //  varchar(45)
     *            'cel_phone',  //  varchar(45)
     *
     *          ]
     *
     *  # Mandatory
     *
     * @return array
     */
    private function addUser(Request $request)
    {

        $this->resetError();
        Log::debug("{$this->SERVICENAME}: (addUser). add new user request.");

        $ok = $this->validate($request, [
            'email'              => [
                'required',
                'email',
                // Considerar a los usuarios que no existen en remoto pero si en local
                //Rule::unique('user_profiles', 'email')
                //    ->where('companies_id', ($this->company ? $this->company->id : null))
                //    ->whereNull('deleted_at'),
            ],
            'password'                => 'required|min:5|max:190|confirmed',      //  varchar(191)  #
            'password_confirmation'   => 'required',
            'first_name'              => 'sometimes|string|max:190',       //  varchar(191)
            'last_name'               => 'sometimes|string|max:190',       //  varchar(191)
            'birth_date'              => 'sometimes|date',                 //  datetime
            'address'                 => 'sometimes|string|max:190',       //  varchar(191)
            'external_number'         => 'sometimes|string|max:100',       //  varchar(191)
            'internal_number'         => 'sometimes|string|max:100',       //  varchar(191)
            'postal_code'             => 'sometimes|numeric|digits_between:5,10',       //  varchar(191)
            'municipality'            => 'sometimes|string|max:190',       //  varchar(191)
            'city'                    => 'sometimes|string|max:190',       //  varchar(191)
            'gender'                  => 'sometimes|in:male,female',       //  enum('male','female')
            'phone'                   => 'sometimes|string|max:45',        //  varchar(45)
            'cel_phone'               => 'sometimes|string|max:45',        //  varchar(45)
        ],
            [
                'email.unique'   => __('client-sites-sync.email-in-use'),
                'email.email'    => __('client-sites-sync.email-format'),
                'email.required' => __('client-sites-sync.email-missing'),
                'password.min'   => __('messages.user-password-short'),
            ]);


        if(!$ok){
            Log::error("{$this->SERVICENAME} Error: (addUser). message: {$this->error_msj}, errors: ".json_encode($this->errors));
            return ['status' => 'error', 'message' => $this->error_msj, 'errors'=>$this->errors,'code' => 403];
        }

        // Localizar usuario previo
        $profile = UserProfile::where(
            [
                ['companies_id', $this->company->id],
                ['email', $request->get('email')],
            ]
        )->withTrashed()->first();

        // El usuario ya existe en BUQ, actualizar sus datos en base al sistema remoto
        if ($profile) {
          Log::debug("{$this->SERVICENAME}: (addUser). User '".$request->get('email')."' already exists. Update.");

          if (!$profile->isVerified()) {
            Log::error("{$this->SERVICENAME} Error: (addUser). message: ".__('messages.user-not-verified').", errors=null");
            return ['status' => 'error', 'message' => __('messages.user-not-verified'), 'errors'=>null,'code' => 403];
          }

            if (!$profile->isVerified()) {
                Log::error("{$this->SERVICENAME} Error: (addUser). message: ".__('messages.user-not-verified').", errors=null");
                return ['status' => 'error', 'message' => __('messages.user-not-verified'), 'errors'=>null,'code' => 403];
            }

            // first_name: Es requerido por CatalogUserProfile para permitir actualizaciones
            if(!$request->has('first_name')){
                if(!empty($profile->first_name)){
                    $first_name = $profile->first_name;
                }else{
                    $first_name = $profile->email;
                }
                $op_tmp = [
                    'first_name' => $first_name
                ];
                request()->merge($op_tmp);
            }

            if(!empty($profile->deleted_at)){
                $profile->restore();
            }

            //Update request
            $options = [
                'id'           => $profile->id,
                'users_id'     => $profile->users_id,
                //'status'       => 'on',
                'companies_id' => $this->company->id,
                'filters'      => [
                    [
                        'name'  => 'comp_id',
                        'value' => $this->company->id,
                    ],
                ],
            ];
            request()->merge($options);
            $request = request();

            $profileUpdated = CatalogFacade::save($request, CatalogUserProfile::class, $profile->profileCatalog);

        }else{
          Log::debug("{$this->SERVICENAME}: (addUser). User '".$request->get('email')."'. Create.");
          $email    = $request->get('email');
          $password = $request->get('password');
          $profile        = LibUsers::createProfileByEmailAndCompany($request, $email, $this->company, $password);
          $profile->token = null;
        }


        //return response()->json(['url' => $company->mailWelcomeInfo->correct_url ?? null]);

        return ['status' => 'ok', 'message' => 'ok', 'code' => 200];
    }



    /**
     * Update local user from client site request
     *
     * @return array
     */
    private function updateUser(Request $request)
    {

        $this->resetError();
        Log::debug("{$this->SERVICENAME}: (addUser). update user request.");
        $ok = $this->validate($request, [
            'email'              => [
                'required',
                'email',
                Rule::exists('user_profiles')->where(function ($query) {
                    $query->where('companies_id', ($this->company ? $this->company->id : null))
                        ->whereNull('deleted_at');
                }),
            ],
            'new-email'              => [
                'sometimes',
                'email',
            ],
            'password'                => 'sometimes|min:5|max:190|confirmed',      //  varchar(191)  #
            'password_confirmation'   => 'sometimes',
            'first_name'              => 'sometimes|required|string|max:190',       //  varchar(191)
            'last_name'               => 'sometimes|string|max:190',       //  varchar(191)
            'birth_date'              => 'sometimes|date|date_format:Y-m-d',                 //  datetime
            'address'                 => 'sometimes|string|max:190',       //  varchar(191)
            'external_number'         => 'sometimes|string|max:100',       //  varchar(191)
            'internal_number'         => 'sometimes|string|max:100',       //  varchar(191)
            'postal_code'             => 'sometimes|numeric|digits_between:5,10', //  varchar(191)
            'municipality'            => 'sometimes|string|max:190',       //  varchar(191)
            'city'                    => 'sometimes|string|max:190',       //  varchar(191)
            'gender'                  => 'sometimes|in:male,female',       //  enum('male','female')
            'phone'                   => 'sometimes|string|max:45',        //  varchar(45)
            'cel_phone'               => 'sometimes|string|max:45',        //  varchar(45)
        ],
            [
                'email.exists'    => __('client-sites-sync.update-email-not-found'),
                'email.email'     => __('client-sites-sync.email-format'),
                'email.required'  => __('client-sites-sync.email-missing'),
                'new-email.email' => __('client-sites-sync.newemail-format'),
                'password.min'    => __('messages.user-password-short'),
            ]);


        if(!$ok){
            Log::error("{$this->SERVICENAME} Error : (updateUser). message: {$this->error_msj}, errors: ".json_encode($this->errors));
            return ['status' => 'error', 'message' => $this->error_msj, 'errors'=>$this->errors,'code' => 403];
        }


        // Localizar usuario previo
        $profile = UserProfile::where(
            [
                ['companies_id', $this->company->id],
                ['email', $request->get('email')],
            ]
        )->first();

        if (!$profile) {
            Log::error("{$this->SERVICENAME} Error: (updateUser). message: ".__('client-sites-sync.username-not-found').", errors=null");
            return ['status' => 'error', 'message' => __('client-sites-sync.username-not-found'), 'errors'=>null,'code' => 403];
        }

        Log::debug("{$this->SERVICENAME}: (updateUser). User '".$request->get('email')."' found. Update.");

        // first_name: Es requerido por CatalogUserProfile para permitir actualizaciones
        if(!$request->has('first_name')){
            if(!empty($profile->first_name)){
                $first_name = $profile->first_name;
            }else{
                $first_name = $profile->email;
            }
            $op_tmp = [
                'first_name' => $first_name
            ];
            request()->merge($op_tmp);
        }


        // Verificar cambio de email
        if($request->has('new-email')){
          $old_email = $profile->email;
          $new_email = $request->get('new-email');
          Log::debug("{$this->SERVICENAME}: (updateUser). New email set to '".$new_email."'. Update.");

            // verificar que no esta usado por otra cuenta
            $profile_new = UserProfile::where(
                [
                    ['id','<>', $profile->id],
                    ['companies_id', $this->company->id],
                    ['email', $new_email],
                ]
            )->first();
          if($profile_new){
            Log::error("{$this->SERVICENAME} Error: (updateUser). message: Solucitud de cambio de mail para usuario '{$old_email}', email '{$new_email}' ya existe, errors=null");
            return ['status' => 'error', 'message' => "Solicitud de cambio de mail para usuario '{$old_email}', email '{$new_email}' ya existe.", 'errors'=>null,'code' => 403];
          }

          // 2020-08-10: Se indica no actualizar email de la tabla users, solo profiles.
          unset($request['new-email']);
          $request->merge(['email' => $new_email]);
        }

        if($request->has('password') && (!empty($request->get('password')))){
            Log::debug("{$this->SERVICENAME}: (verifyUser). Password set in request:  '".$request->get('password')."'");
            $profile->password = $request->get('password');
            $profile->save();
        }

        //Update request
        $options = [
            'id'           => $profile->id,
            'users_id'     => $profile->users_id,
            'status'       => 'on',
            'companies_id' => $this->company->id,
            'filters'      => [
                [
                    'name'  => 'comp_id',
                    'value' => $this->company->id,
                ],
            ],
        ];
        request()->merge($options);
        $request = request();

        $profileUpdated = CatalogFacade::save($request, CatalogUserProfile::class, $profile->profileCatalog);
        Log::debug("{$this->SERVICENAME}: (updateUser) User Updated.");

        return ['status' => 'ok', 'message' => 'ok', 'code' => 200];
    }



    /**
     * Update local user from client site request
     *
     * @return array
     */
    private function setPassword(Request $request)
    {

        $this->resetError();
        Log::debug("{$this->SERVICENAME}: (setPassword). update password request.");
        $ok = $this->validate($request, [
            'email'              => [
                'required',
                'email',
                Rule::exists('user_profiles')->where(function ($query) {
                    $query->where('companies_id', ($this->company ? $this->company->id : null))
                        ->whereNull('deleted_at');
                }),
            ],
            'password'                => 'required|min:5|max:190|confirmed',      //  varchar(191)  #
            'password_confirmation'   => 'required',
        ],
            [
                'email.exists'   => __('client-sites-sync.update-email-not-found'),
                'email.email'    => __('client-sites-sync.email-format'),
                'email.required' => __('client-sites-sync.email-missing'),
                'password.min'   => __('messages.user-password-short'),
            ]);


        if(!$ok){
            Log::error("{$this->SERVICENAME} Error: (setPassword). message: {$this->error_msj}, errors: ".json_encode($this->errors));
            return ['status' => 'error', 'message' => $this->error_msj, 'errors'=>$this->errors,'code' => 403];
        }

        // Localizar usuario previo
        $profile = UserProfile::where(
            [
                ['companies_id', $this->company->id],
                ['email', $request->get('email')],
            ]
        )->first();

        if (!$profile) {
            Log::error("{$this->SERVICENAME} Error: (setPassword). message: ".__('client-sites-sync.username-not-found').", errors=null");
            return ['status' => 'error', 'message' => __('client-sites-sync.username-not-found'), 'errors'=>null,'code' => 403];
        }

        Log::debug("{$this->SERVICENAME}: (setPassword). Set password to: ".$request->get('password'));
        $profile->password = $request->get('password');
        $profile->save();
        event(new PasswordReset($profile));
        //$this->guard()->login($user);

        Log::debug("{$this->SERVICENAME}: (setPassword). Password set.");
        return ['status' => 'ok', 'message' => 'ok', 'code' => 200];
    }


    /**
     * Delete local user from client site request
     *
     * @return array
     */
    private function deleteUser(Request $request)
    {

        $this->resetError();
        $ok = $this->validate($request, [
            'email'              => [
                'required',
                'email',
                Rule::exists('user_profiles')->where(function ($query) {
                    $query->where('companies_id', ($this->company ? $this->company->id : null))
                        ->whereNull('deleted_at');
                }),
            ],
        ],
            [
                'email.exists'   => __('client-sites-sync.delete-email-not-found'),
                'email.email'    => __('client-sites-sync.email-format'),
                'email.required' => __('client-sites-sync.email-missing'),
            ]);


        if(!$ok){
            Log::error("{$this->SERVICENAME} Error: (deleteUser). message: {$this->error_msj}, errors: ".json_encode($this->errors));
            return ['status' => 'error', 'message' => $this->error_msj, 'errors'=>$this->errors,'code' => 403];
        }

        // Localizar usuario previo
        $profile = UserProfile::where(
            [
                ['companies_id', $this->company->id],
                ['email', $request->get('email')],
            ]
        )->first();

        if (!$profile) {
            Log::error("{$this->SERVICENAME} Error: (deleteUser). message: ".__('client-sites-sync.username-not-found').", errors=null");
            return ['status' => 'error', 'message' => __('client-sites-sync.username-not-found'), 'errors'=>null,'code' => 403];
        }

        //$request->merge(['id' => $profile->id]);
        //$updated = CatalogFacade::delete($request, CatalogUserProfile::class);
        $profile->status = 'inactive';
        $profile->save();

        return ['status' => 'ok', 'message' => 'ok', 'code' => 200];
    }


    /**
     * Restore deleted local user from client site request
     *
     * @return array
     */
    private function restoreUser(Request $request)
    {

        $this->resetError();
        $ok = $this->validate($request, [
            'email'              => [
                'required',
                'email',
                Rule::exists('user_profiles')->where(function ($query) {
                    $query->where('companies_id', ($this->company ? $this->company->id : null))
                        ->whereNotNull('deleted_at');
                }),
            ],
        ],
            [
                'email.exists'   => __('client-sites-sync.username-not-deleted'),
                'email.required' => __('messages.user-email-missing'),
                'email.email'    => __('messages.user-email-missing'),
            ]);


        if(!$ok){
            Log::error("{$this->SERVICENAME} Error: (restoreUser). message: {$this->error_msj}, errors: ".json_encode($this->errors));
            return ['status' => 'error', 'message' => $this->error_msj, 'errors'=>$this->errors,'code' => 403];
        }

        // Localizar usuario previo
        $profile = UserProfile::withTrashed()->where(
            [
                ['companies_id', $this->company->id],
                ['email', $request->get('email')],
            ]
        )->first();

        if (!$profile) {
            Log::error("{$this->SERVICENAME} Error: (restoreUser). message: ".__('client-sites-sync.username-not-found').", errors=null");
            return ['status' => 'error', 'message' => __('client-sites-sync.username-not-found'), 'errors'=>null,'code' => 403];
        }

        // Update user
        $user = UserProfile::withTrashed()->find($profile->id);
        $user->restore();


        return ['status' => 'ok', 'message' => 'ok', 'code' => 200];
    }


    /**
     * Create/Update It's Remote Webhook in ordfer to receive Notifications at it's Site
     *
     *
     * @return array
     */
    private function setRemoteWebhook(Request $request)
    {

        $this->resetError();
        $ok = $this->validate($request, [
            'webhook.*' => 'required|url',
        ],
            [
                'required' => __('client-sites-sync.webhook-missing'),
                'url'      => __('client-sites-sync.webhook-url'),
            ]);

        Log::debug("{$this->SERVICENAME} setRemoteWebhook: data: ".json_encode($request->all()));

        if(!$ok){
            Log::error("{$this->SERVICENAME} Error: (setRemoteWebhook). message: {$this->error_msj}, errors: ".json_encode($this->errors));
            return ['status' => 'error', 'message' => $this->error_msj, 'errors'=>$this->errors,'code' => 403];
        }


        // ----*** Sync Company Webhooks ***----
        $webhooks = $request->get('webhook',[]);
        $this->company->webhooks()->delete();
        if(count($webhooks)>0){
            foreach ($webhooks as $webhook_value) {
                if(!empty($webhook_value)){
                    $this->company->webhooks()->create(['webhook'=>$webhook_value]);
                }
            }
        }
        // ----*** Sync Company Webhooks ***----

        return ['status' => 'ok', 'message' => 'ok', 'code' => 200];
    }


}
