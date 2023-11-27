<?php

namespace App\Http\Controllers\Api\OnBoarding;

use App\Admin;
use App\Http\Controllers\Controller;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Brand\CatalogCombo;
use App\Librerias\Catalog\Tables\Brand\CatalogLocation;
use App\Librerias\Catalog\Tables\Brand\CatalogMembership;
use App\Librerias\Catalog\Tables\Brand\CatalogService;
use App\Librerias\Catalog\Tables\Company\CatalogCompany;
use App\Librerias\Catalog\Tables\Brand\CatalogBrand;
use App\Librerias\Catalog\Tables\Brand\CatalogCredits;
use App\Librerias\Catalog\Tables\Location\CatalogRoom;
use App\Librerias\Models\Admin\LibAdmin;
use App\Librerias\Models\Users\LibUsers;
use App\Librerias\Permissions\Role;
use App\Models\Admin\AdminProfile;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Countries;
use App\Models\CountryState;
use App\Models\Credit\Credit;
use App\Models\Leads;
use App\Models\Payment\BrandPaymentType;
use App\Models\Payment\PaymentType;
use App\Models\User\UserProfile;
use App\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Crypt;
use Stripe\Stripe;

class LeadsApiController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function create(Request $request)
    {
        $user = User::where('name', $request->username)->first();
        $leads = Leads::where('username', $request->username)->first();

        // Abortar si el usuario está previamente registrado en Gafafit, pero no en el asistente
        if (isset($user) && !isset($leads)) {
            return abort(403, __('auth.username_in_use'));
        }

        if (!$request->is_new && !isset($leads)) {
            return abort(403, __('auth.not_found'));
        }

        if (isset($leads)) {
            if (Hash::check($request->password, $leads->getAuthPassword())) {
                return response()->json([
                    'token'   => $leads->createToken('Personal Access Token')->accessToken,
                    'step'    => $leads->step_name,
                    'company' => $leads->company,
                    'message' => 'success',
                ]);
            }

            return abort(403, __('auth.failed'));
        }

        $this->validate($request, [
            'firstname' => 'nullable',
            'lastname'  => 'nullable',
            'step_name' => 'nullable',
            'password'  => 'required|string|min:5|confirmed',
            'username'  => [
                'required',
                //                'unique:leads',
                'email',
                Rule::unique('users', 'email'),
                Rule::unique('admins', 'email'),
            ],
        ], [
            'username.unique'   => __('messages.username-in-use'),
            'username.required' => __('messages.user-email-missing'),
            '*.required'        => __('messages.user-name-missing'),
            'password.min'      => __('messages.user-password-short'),
        ]);

        $leads               = new Leads;
        $leads->firstname    = $request->firstname;
        $leads->lastname     = $request->lastname;
        $leads->step_name    = 'login';
        $leads->company      = '';
        $leads->username     = $request->username;
        $leads->password_raw = Crypt::encryptString($request->password);
        $leads->password     = Hash::make($request->password);
        $leads->save();

        return response()->json([
            'token'   => $leads->createToken('Personal Access Token')->accessToken,
            //'step'    => 'initial',
            'company' => '',
            'message' => 'success',
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function company2(Request $request): JsonResponse
    {
        $lead = Auth::user();

        $request->validate([
            //        $this->validate($request, [
            'name'              => [
                'required',
                'max:100',
                Rule::unique('companies', 'name'),
                Rule::unique('brands', 'name'),
            ],
            'email'             => 'required|email',
            'subscription_plan' => 'required',
            'token'             => 'required',
            'logo'              => 'nullable',
        ], [
            'name.unique' => __('messages.company-name-in-use'),
            'slug.unique' => __('messages.company-slug-in-use'),
            '*.required'  => __('messages.user-name-missing'),
        ]);

        $request->merge([
            'slug'        => str_slug($request->name, '-'),
            'mail_from'   => $request->email,
            'name_from'   => $request->name,
            'status'      => 'on',
            'language_id' => 1 // es
        ]);

        //
        DB::transaction(function () use ($request, $lead) {
            // creando empresa
            $company = CatalogFacade::save($request, CatalogCompany::class);

            // datos necesarios para crear marca
            $request->merge([
                'currencies_id'         => 1, //MXN
                'companies_id'          => $company->id,
                'company'               => $company,
                'waitlist'              => 'on',
                'status'                => 'on',
                'max_waitlist'          => 25,                   // %
                'cancelation_dead_line' => 12,                   //cancelClassHours
                'time_zone'             => 'America/Mexico_City' //
            ]);

            $brand = CatalogFacade::save($request, CatalogBrand::class);

            // crear creditos
            $request->merge([
                'brands_id'    => $brand->id,
                'companies_id' => $company->id,
                //'status'       => 'on',
            ]);

            CatalogFacade::save($request, CatalogCredits::class);

            // crear users y users_profile con empresa
            $requestUser = $request->duplicate();
            $pass        = Crypt::decryptString($lead->password_raw);
            $requestUser->merge([
                'first_name'            => $lead->firstname,
                'last_name'             => $lead->lastname,
                'role'                  => 'admin',
                'password'              => $pass,
                'password_confirmation' => $pass,
                //'status'                => 'on',
            ]);

            $companyModel = Company::find($company->id);
            LibUsers::createProfileByEmailAndCompany($requestUser, $lead->username, $companyModel, $pass);

            $user           = User::where('email', $lead->username)->first();
            $user->role     = 'admin';
            $user->status   = 'active';
            //$user->verified = true;
            //            $user->password = $lead->getAuthPassword();
            $user->save();

            // Crear la suscripción en Stripe
            Stripe::setApiKey(config('stripe.secret'));

            $user->newSubscription('main', $request->subscription_plan['id'])->create($request->token);

            // crear admins y admins_profile con empresa
            $requestAdmin = $request->duplicate();
            $requestAdmin->merge([
                'email'                 => $lead->username,
                'first_name'            => $lead->firstname,
                'last_name'             => $lead->lastname,
                'current_company'       => $company,
                'companies_id'          => (int) $company->id,
                'password'              => $pass,
                'password_confirmation' => $pass,
                //'status'                => 'on',
            ]);
            $admin               = LibAdmin::createProfileByEmailAndCompany($requestAdmin);
            $admin->companies_id = $company->id;
            $admin->status       = 'active';
            $admin->save();

            $adminModel           = Admin::where('email', $lead->username)->first();
            $adminModel->password = $pass;
            $admin->status        = 'active';
            $adminModel->save();

            // assigned_roles
            $gafaSaasRole = Role::where('title', 'Gafa SaaS')->whereNull('companies_id')->get()->first();

            $requestRole = $request->duplicate();
            $requestRole->merge([
                'rolgafafit'   => $gafaSaasRole->id,
                'rolcompanies' => [$company->id => 4],
                'rolbrands'    => [$brand->id => 5],
            ]);
            LibAdmin::assignRoles($requestRole, $adminModel, $company);

            // Actualizar datos del Leads
            $lead->company = $company->name;
            $lead->save();
        });

        return response()->json([
            'message' => 'success',
            'token'   => User::where('email', $lead->username)->first()->createToken('Personal Access Token')->accessToken,
            'company' => $lead->company,
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function company(Request $request): JsonResponse
    {
        $user = User::where('name', $request->username)->first();
        $leads = Leads::where('username', $request->username)->first();

        // Abortar si el usuario está previamente registrado en Gafafit, pero no en el asistente
        if (isset($user) && !isset($leads)) {
            return abort(403, __('auth.username_in_use'));
        }

        if (isset($leads)) {
            if (Hash::check($request->password, $leads->getAuthPassword())) {
                return response()->json([
                    'token'   => $leads->createToken('Personal Access Token')->accessToken,
                    'step'    => $leads->step_name,
                    'company' => $leads->company,
                    'message' => 'success',
                ]);
            }

            return abort(403, __('auth.failed'));
        }

        $email     = $request->email;
        $firstname = $request->firstname;
        $lastname  = $request->lastname;
        $name      = trim($firstname.' '.$lastname);
        $password  = strrev($email); // Estrategia: la contraseña será el correo, al revés

        $request->merge(['email' => $email]);

        // 2. Crear la empresa
        $request->validate([
            //        $this->validate($request, [
            'name'  => [
                'required',
                'max:100',
                Rule::unique('companies', 'name'),
                Rule::unique('brands', 'name'),
            ],
            'email' => 'required|email',
            'logo'  => 'nullable',
        ], [
            'name.unique' => __('messages.company-name-in-use'),
            'slug.unique' => __('messages.company-slug-in-use'),
            '*.required'  => __('messages.user-name-missing'),
        ]);

        $request->merge([
            'slug'        => str_slug($name, '-'),
            'mail_from'   => $email,
            'name_from'   => $name,
            'status'      => 'on',
            'language_id' => 1 // es
        ]);

        // Crear entrada en Leads
        $lead            = new Leads;
        $lead->firstname = $firstname;
        $lead->lastname  = $lastname;
        $lead->company   = $request->name;
        //$lead->username     = sprintf('%s-%s@%s', str_before($json['username'], '@'), str_random(5), str_after($json['username'], '@'));
        $lead->username     = $email;
        $lead->password_raw = Crypt::encryptString($password);
        $lead->password     = Hash::make($password);
        $lead->step_name    = 'company';

        DB::transaction(function () use ($request, $lead) {
            $client = new \GuzzleHttp\Client(['timeout' => 300]);

            try {
                // Get a token to be used on next API calls
                $json_response = $client->request('POST', config('gafapay.api_url').'/api/oauth/token', [
                    'form_params' => [
                        'grant_type'    => config('gafapay.grant_type'),
                        'client_id'     => config('gafapay.client_id'),
                        'client_secret' => config('gafapay.client_secret'),
                        'username'      => config('gafapay.username'),
                        'password'      => config('gafapay.password'),
                    ],
                    //'proxy'       => 'http://localhost:3128',
                ]);

                $json = json_decode((string) $json_response->getBody(), true);
                //$json = $json_response->getBody();

                // Successfully got a token!
                $access_token = $json[0]['access_token'];

                // Register the brand on GafaPay
                try {
                    $json_response2 = $client->request('POST', config('gafapay.api_url').'/api/brands', [
                        'headers'     => [
                            'Authorization' => 'Bearer '.$access_token,
                            'Content-Type'  => 'application/x-www-form-urlencoded',
                            'Accept'        => 'application/json',
                        ],
                        'form_params' => [
                            'name'        => $request->name,
                            'description' => $request->name,
                        ],
                        //'proxy'       => 'http://localhost:3128',
                    ]);

                    $json2 = json_decode((string) $json_response2->getBody(), true);
                    //$json = $json_response->getBody();

                    //
                    $gafapay_brand_data = $json2['data'];

                    // Do everything needed on Gafafit (including to register the brand itself on Gafafit!)
                    // Crear la entrada en Leads
                    $lead->save();

                    // creando empresa
                    $company = CatalogFacade::save($request, CatalogCompany::class);

                    // datos necesarios para crear marca
                    $request->merge([
                        'currencies_id'         => 1, //MXN
                        'companies_id'          => $company->id,
                        'company'               => $company,
                        'waitlist'              => 'on',
                        'status'                => 'on',
                        'max_waitlist'          => 25,                    // %
                        'cancelation_dead_line' => 12,                    //cancelClassHours
                        'time_zone'             => 'America/Mexico_City', //
                        'gafapay_client_id'     => $gafapay_brand_data['oauth_client_id'],
                        'gafapay_client_secret' => $gafapay_brand_data['oauth_client_secret'],
                        'gafapay_brand_id'      => $gafapay_brand_data['id'],
                    ]);

                    $brand = CatalogFacade::save($request, CatalogBrand::class);

                    // crear creditos
                    $request->merge([
                        'brands_id'    => $brand->id,
                        'companies_id' => $company->id,
                        //'status'       => 'on',
                    ]);

                    CatalogFacade::save($request, CatalogCredits::class);

                    // crear users y users_profile con empresa
                    $requestUser = $request->duplicate();
                    $pass        = Crypt::decryptString($lead->password_raw);
                    $requestUser->merge([
                        'first_name'            => $lead->firstname,
                        'last_name'             => $lead->lastname,
                        'role'                  => 'admin',
                        'password'              => $pass,
                        'password_confirmation' => $pass,
                        //'status'                => 'on',
                    ]);

                    $companyModel = Company::find($company->id);
                    LibUsers::createProfileByEmailAndCompany($requestUser, $lead->username, $companyModel, $pass);

                    $user         = User::where('email', $lead->username)->first();
                    $user->role   = 'admin';
                    $user->status = 'active';
                    //$user->verified = true;
                    //$user->password = $lead->getAuthPassword();
                    $user->save();

                    // crear admins y admins_profile con empresa
                    $requestAdmin = $request->duplicate();
                    $requestAdmin->merge([
                        'email'                 => $lead->username,
                        'first_name'            => $lead->firstname,
                        'last_name'             => $lead->lastname,
                        'current_company'       => $company,
                        'companies_id'          => (int) $company->id,
                        'password'              => $pass,
                        'password_confirmation' => $pass,
                        //'status'                => 'on',
                    ]);
                    $admin               = LibAdmin::createProfileByEmailAndCompany($requestAdmin);
                    $admin->companies_id = $company->id;
                    $admin->status       = 'active';
                    $admin->save();

                    $adminModel           = Admin::where('email', $lead->username)->first();
                    $adminModel->password = $pass;
                    $admin->status        = 'active';
                    $adminModel->save();

                    // assigned_roles
                    $gafaSaasRole = Role::where('title', 'Gafa SaaS')->whereNull('companies_id')->get()->first();

                    $requestRole = $request->duplicate();
                    $requestRole->merge([
                        'rolgafafit'   => $gafaSaasRole->id,
                        'rolcompanies' => [$company->id => 4],
                        'rolbrands'    => [$brand->id => 5],
                    ]);
                    LibAdmin::assignRoles($requestRole, $adminModel, $company);

                    // Actualizar datos del Leads
                    $lead->company = $company->name;
                    $lead->save();
                } catch (\Exception $e2) {
                    return abort(403, $e2->getMessage());
                }
            } catch (\Exception $e) {
                return abort(403, $e->getMessage());
            }
        });

        return response()->json([
            'message' => 'success',
            //'token'   => User::where('email', $json['username'])->first()->createToken('Personal Access Token')->accessToken,
            'token'   => $lead->createToken('Personal Access Token')->accessToken,
            'company' => $request->name,
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function countries(Request $request): JsonResponse
    {
        $countries = Countries::orderBy('name')->get(['id', 'name']);

        return response()->json($countries);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function states(Request $request): JsonResponse
    {
        $country_id = $request->countries_id ?? null;
        $states     = [];

        if ($country_id) {
            $country = Countries::where('id', $country_id)->get()->first();

            $states = CountryState::where('country_code', $country->code)->orderBy('name')->get(['id', 'name']);
        }

        return response()->json($states);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function locations(Request $request): JsonResponse
    {
        $lead = Auth::user();

        $request->validate([
            'locations'        => ['required'],
            'locations.*.name' => [
                'required',
                //Rule::unique('locations', 'name'),
                'unique:locations,name',
            ],
        ], [
            '*.required' => __('messages.user-name-missing'),
            'locations.*.name.unique' => __('messages.location-name-in-use'),
        ]);

        DB::transaction(static function () use ($request, $lead) {
            $company       = Company::where('name', $request->company_name)->first();
            $brand         = Brand::where('name', $request->company_name)->first();
            $companyCredit = Credit::where(['companies_id' => $company->id, 'brands_id' => $brand->id])->first();

            // Crear las ubicaciones
            $locations = $request->locations;

            foreach ($locations as $location) {
                // Crear la ubicación
                $request->merge([
                    'name'              => $location['name'],
                    'slug'              => str_slug($location['name'], '-'),
                    'phone'             => $location['contactPhone'] ?? null,
                    'email'             => $location['contactEmail'],
                    'street'            => $location['street'] ?? null,
                    'number'            => $location['number'] ?? null,
                    'suburb'            => $location['suburb'] ?? null,
                    'postcode'          => $location['postcode'] ?? null,
                    //'district'          => $location['district'],
                    'country_states_id' => $location['country_states_id'] ?? null,
                    'countries_id'      => $location['countries_id'] ?? null,
                    'city'              => $location['city'] ?? null,
                    'calendar_days'     => 14,
                    'status'            => 'on',
                    'companies_id'      => $company->id,
                    'brands_id'         => $brand->id,
                ]);

                $newLocation = CatalogFacade::save($request, CatalogLocation::class);

                // Crear el crédito para la ubicación
                $request->merge([
                    //'companies_id' => $company->id,
                    //'brands_id'    => $brand->id,
                    'locations_id' => $newLocation->id,
                    'status'       => 'on',
                ]);

                $newCredit = CatalogFacade::save($request, CatalogCredits::class);

                // La ubicación no es gestionada en el catálogo, así que debe hacerse en el paso siguiente!!!
                $newCredit->locations_id = $newLocation->id;
                $newCredit->save();

                // Crear los salones
                $rooms = $location['classRoom'];

                foreach ($rooms as $room) {
                    $request->merge([
                        'name'     => $room['name'],
                        'details'  => ($room['map'] === 1) ? 'map' : 'quantity',
                        'capacity' => $room['capacity'],
                        'status'   => 'on',
                        //'companies_id' => $company->id,
                        //'brands_id'    => $brand->id,
                        //'locations_id' => $newLocation->id,
                    ]);
                    $newRoom = CatalogFacade::save($request, CatalogRoom::class);
                }

                // Crear los servicios
                $services = $location['services'];

                foreach ($services as $service) {
                    $request->merge([
                        'name'   => $service['name'],
                        'order'  => 0,
                        'status' => 'on',
                        //'companies_id' => $company->id,
                        //'brands_id'    => $brand->id,
                    ]);

                    /** @var \App\Models\Service $newService */
                    $newService = CatalogFacade::save($request, CatalogService::class);

                    // Asociar con los créditos de la marca y de la ubicación
                    $newService->credits()->attach([$companyCredit->id, $newCredit->id]);
                }
            }
        });

        return response()->json([
            'message' => 'success',
            'token'   => User::where('email', $lead->username)->first()->createToken('Personal Access Token')->accessToken,
            'company' => $request->company_name,
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function credits(Request $request): JsonResponse
    {
        $company = Company::where('name', $request->company)->first();
        $brand   = Brand::where('name', $request->company)->first();

        $credits = DB::table('credits')->join('locations', 'credits.locations_id', '=', 'locations.id', 'left')->where('credits.companies_id', $company->id)->where('credits.brands_id', $brand->id)->select([
            'credits.id',
            'credits.name',
            'locations.name AS location_name',
        ])->get();

        return response()->json($credits);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function memberships(Request $request): JsonResponse
    {
        $lead = Auth::user();

        //$request->validate(['memberships' => ['required']], ['*.required' => __('messages.user-name-missing')]);

        DB::transaction(static function () use ($request, $lead) {
            $company = Company::where('name', $request->company_name)->first();
            $brand   = Brand::where('name', $request->company_name)->first();

            // Crear las membresías
            $memberships = $request->memberships;

            foreach ($memberships as $membership) {
                // Crear la membresía
                $request->merge([
                    'name'                    => $membership['name'],
                    'companies_id'            => $company->id,
                    'brands_id'               => $brand->id,
                    'price'                   => $membership['price'],
                    'order'                   => 0,
                    'meeting_max_reservation' => (int) $membership['meeting_max_reservation'],
                    'global_purchase'         => 1,
                    'status'                  => 'on',
                    'credits_id'              => $membership['credits_id'],
                ]);

                $newMembership = CatalogFacade::save($request, CatalogMembership::class);
            }

            // Crear los paquetes
            $combos = $request->combos;

            foreach ($combos as $combo) {
                // Crear el paquete
                $request->merge([
                    'name'         => $combo['name'],
                    'companies_id' => $company->id,
                    'brands_id'    => $brand->id,
                    'credits_id'   => $combo['credits_id'],
                    'credits'      => $combo['credits'],
                    'price'        => $combo['price'],
                    'status'       => 'on',
                ]);

                $newCombo = CatalogFacade::save($request, CatalogCombo::class);
            }
        });

        return response()->json([
            'message' => 'success',
            'token'   => User::where('email', $lead->username)->first()->createToken('Personal Access Token')->accessToken,
            'company' => $request->company_name,
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function typesOfPayments(Request $request): JsonResponse
    {
        $client = new \GuzzleHttp\Client(['timeout' => 300]);

        try {
            $json_response = $client->request('POST', config('gafapay.api_url').'/api/oauth/token', [
                'form_params' => [
                    'grant_type'    => config('gafapay.grant_type'),
                    'client_id'     => config('gafapay.client_id'),
                    'client_secret' => config('gafapay.client_secret'),
                    'username'      => config('gafapay.username'),
                    'password'      => config('gafapay.password'),
                ],
                //'proxy'       => 'http://localhost:3128',
            ]);

            $json = json_decode((string) $json_response->getBody(), true);
            //$json = $json_response->getBody();

            // Successfully got a token! Get payment systems
            $access_token = $json[0]['access_token'];
            try {
                $json_response2 = $client->request('GET', config('gafapay.api_url').'/api/paymentsystems', [
                    'headers' => [
                        'Authorization' => 'Bearer '.$access_token,
                        'Accept'        => 'application/json',
                    ],
                    //'proxy'   => 'http://localhost:3128',
                ]);

                $json2 = json_decode((string) $json_response2->getBody(), true);
                //$json = $json_response->getBody();

                //
                $payment_systems = [];
                foreach ($json2['data'] as $payment_system) {
                    if (null === $payment_system['fechaEliminacion']) {
                        $payment_systems[] = [
                            'id'   => $payment_system['id'],
                            'name' => $payment_system['nombre'],
                            'slug' => $payment_system['detalles'],
                        ];
                    }
                }
            } catch (\Exception $e2) {
                return abort(403, $e2->getMessage());
            }
        } catch (\Exception $e) {
            return abort(403, $e->getMessage());
        }

        return response()->json($payment_systems);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function payments(Request $request): JsonResponse
    {
        $lead = Auth::user();

        //$request->validate(['payments' => ['required']], ['*.required' => __('messages.user-name-missing')]);

        DB::transaction(static function () use ($request, $lead) {
            $client = new \GuzzleHttp\Client(['timeout' => 300]);

            try {
                // Get a token to be used on next API calls
                $json_response = $client->request('POST', config('gafapay.api_url').'/api/oauth/token', [
                    'form_params' => [
                        'grant_type'    => config('gafapay.grant_type'),
                        'client_id'     => config('gafapay.client_id'),
                        'client_secret' => config('gafapay.client_secret'),
                        'username'      => config('gafapay.username'),
                        'password'      => config('gafapay.password'),
                    ],
                    //'proxy'       => 'http://localhost:3128',
                ]);

                $json = json_decode((string) $json_response->getBody(), true);
                //$json = $json_response->getBody();

                // Successfully got a token!
                $access_token = $json[0]['access_token'];

                //$company = Company::where('name', $request->company_name)->first();
                $brand = Brand::where('name', $request->company_name)->first();

                // Register the payments on GafaPay
                $payments = $request->payments;

                foreach ($payments as $payment) {
                    try {
                        $json_response3 = $client->request('POST', sprintf('%s/api/brands/%d/paymentsystems/%d', config('gafapay.api_url'), $brand->gafapay_brand_id, $payment['type_of_payment']), [
                            'headers'     => [
                                'Authorization' => 'Bearer '.$access_token,
                                'Content-Type'  => 'application/x-www-form-urlencoded',
                                'Accept'        => 'application/json',
                            ],
                            'form_params' => [
                                'apikey'               => $payment['production_public_api_key'],
                                'apisecretkey'         => $payment['production_private_api_key'],
                                'apikey_devmode'       => $payment['development_public_api_key'],
                                'apisecretkey_devmode' => $payment['development_private_api_key'],
                                'env'                  => ($payment['mode'] === 'development') ? 'dev' : 'prod',
                                'hasshipping'          => 1,
                            ],
                            //'proxy'       => 'http://localhost:3128',
                        ]);

                        $json3 = json_decode((string) $json_response3->getBody(), true);
                        //$json3 = $json_response3->getBody();

                        // TODO: Ya no es necesario registrar la forma de pago directamente en Gafafit
                        //$newPayment = new BrandPaymentType();
                        //
                        //$newPayment->brands_id        = $brand->id;
                        //$newPayment->payment_types_id = $payment['type_of_payment'];
                        //$newPayment->config           = json_encode([
                        //    'type'                        => $payment['mode'],
                        //    'production_public_api_key'   => $payment['production_public_api_key'],
                        //    'production_private_api_key'  => $payment['production_private_api_key'],
                        //    'development_public_api_key'  => $payment['development_public_api_key'],
                        //    'development_private_api_key' => $payment['development_private_api_key'],
                        //]);
                        //$newPayment->front            = $payment['front'];
                        //$newPayment->back             = $payment['back'];
                        //
                        //$newPayment->save();
                    } catch (\Exception $e3) {
                        return abort(403, $e3->getMessage());
                    }
                }
            } catch (\Exception $e) {
                return abort(403, $e->getMessage());
            }
        });

        return response()->json([
            'message' => 'success',
            'token'   => User::where('email', $lead->username)->first()->createToken('Personal Access Token')->accessToken,
            'company' => $request->company_name,
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Throwable
     */
    public function finishWizard(Request $request): JsonResponse
    {
        $this->validate($request, [
            'password' => 'required|string|min:5',
        ], [
            '*.required'   => __('messages.user-name-missing'),
            'password.min' => __('messages.user-password-short'),
        ]);

        $lead = Auth::user();

        $username = $lead->username;
        $password = $request->password;

        $client = new \GuzzleHttp\Client(['timeout' => 300]);

        try {
            $json_response = $client->request('POST', 'https://accounts.buq.mx/oauth/token', [
                'form_params' => [
                    'client_id'     => 1,
                    'client_secret' => "znB0QY7LmSd3PbYPC8c3cq9qV1OPQSRXFRaNf0pK",
                    'grant_type'    => 'password',
                    'username'      => $username,
                    'password'      => $password,
                ],
                //'proxy'       => 'http://localhost:3128',
            ]);

            //$json = json_decode((string) $json_response->getBody(), true);
            $json = $json_response->getBody();
        } catch (\Exception $e) {
            return abort(403, $e->getMessage());
        }

        DB::transaction(static function () use ($lead, $username, $password) {
            $user = User::where('email', $username)->first();

            $user->password = $password;
            $user->save();

            $userProfile = UserProfile::where('users_id', $user->id)->first();
            $userProfile->password = $password;
            $userProfile->save();

            $admin = Admin::where('email', $username)->first();

            $admin->password = $password;
            $admin->save();

            $adminProfile = AdminProfile::where('admins_id', $admin->id)->first();
            $adminProfile->password = $password;
            $adminProfile->save();

            $lead->delete();
        });

        return response()->json([
            'message' => 'success',
            'token'   => User::where('email', $username)->first()->createToken('Personal Access Token')->accessToken,
            'company' => $request->company_name,
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     */
    public function updateStep(Request $request)
    {
        $request->validate([
            'username' => ['required'],
            'step'     => ['required'],
        ], [' *.required' => __('messages.user - name - missing')]);

        //$lead = Auth::user();
        $lead = Leads::where('username', $request->username)->first();

        if (! isset($lead)) {
            return abort(403, __('auth.failed'));
        }

        $lead->step_name = $request->step;
        $lead->save();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateToken(Request $request)
    {
        $this->validate($request, [
            'email'     => [
                'required',
                //                'unique:leads',
                'email',
                //Rule::unique('users', 'email'),
                //Rule::unique('admins', 'email'),
            ],
            'firstname' => 'required',
            'lastname'  => 'required',
            'package'   => 'required',
        ], [
            //'username.unique'   => __('messages.username-in-use'),
            '*.required'        => __('messages.user-name-missing'),
        ]);

        $now = Carbon::now();

        $token = [
            'sub'          => $request->email,
            'iat'          => $now->timestamp,
            'exp'          => $now->addHours(24)->timestamp,
            'firstname'    => $request->firstname,
            'lastname'     => $request->lastname,
            'subscription' => $request->package,
        ];

        $jwt = JWT::encode($token, config('app.jwt_secret'));

        return response()->json(['jwt' => $jwt]);
    }
}
