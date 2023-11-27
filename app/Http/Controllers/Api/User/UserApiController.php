<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\ApiController;
use App\Http\Requests\ApiRequest as Request;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\LibCatalogsTable;
use App\Librerias\Catalog\Tables\Company\CatalogUserProfile;
use App\Librerias\Catalog\Tables\Location\CatalogPurchase;
use App\Librerias\Catalog\Tables\Location\Reservations\CatalogReservations;
use App\Librerias\GafaPay\LibGafaPay;
use App\Librerias\Helpers\LibRoute;
use App\Librerias\Models\Users\LibUsers;
use App\Librerias\Reservation\LibReservation;
use App\Librerias\Users\LibUserProfiles;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Purchase\Purchase;
use App\Models\Reservation\Reservation;
use App\Models\Subscriptions\Subscription;
use App\Models\User\UserProfile;
use App\Models\User\UsersCredits;
use App\Rules\Captcha;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Validator;


class UserApiController extends ApiController
{
    use AuthenticatesUsers;

    public function __construct(Request $request = null)
    {
        parent::__construct();

        $company = $this->getCompany();

        $this->middleware(
            function ($request, $next) use ($company) {
                $brand = $request->route('brand');
                $reservation = $request->route('reservation');
                if ($brand->id !== $reservation->brands_id) {
                    abort(404);
                }

                return $next($request);
            }
        )->only(
            [
                'getReservation',
                'reservationCancel',
            ]
        );
    }

    public function index(Request $request)
    {
        $response = CatalogFacade::index($request, CatalogUserProfile::class, $request->get('per_page', null));

        return response()->json($response->getRespuestas());
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    function create(Request $request)
    {
        if (!$request->header('gafafit-company')) {
            abort(403, __('api-user.MessageNoCompany'));
        }
        $company = $this->getCompany();

        $captchaValidation = !isset($request->tokenmovil) ? [
            'g-recaptcha-response' => [
                'bail',
                'required',
                new Captcha($request->captcha_secret_key, $request->remote_addr),
            ],
        ] : [
            'tokenmovil' => 'required',
        ];

        $validator = Validator::make($request->all(), array_merge([
            'username'              => [
                'required',
                'email',
                Rule::unique('user_profiles', 'email')
                    ->where('companies_id', ($company ? $company->id : null))
                    ->whereNull('deleted_at'),
            ],
            'birth_date'            => 'nullable|date',
            'gender'                => 'nullable|in:male,female',
            'password'              => 'required|min:5|confirmed',
            'password_confirmation' => 'required',
        ], $captchaValidation),
            [
                'username.unique'   => __('messages.username-in-use'),
                'username.required' => __('messages.user-email-missing'),
                'username.email'    => __('messages.user-email-missing'),
                '*.required'        => __('messages.user-name-missing'),
                'password.min'      => __('messages.user-password-short'),
            ]
        );

        $validator->after(
            static function ($validator) use ($company, $request) {
                if (isset($request->tokenmovil) && ($request->tokenmovil !== config('app.hard_coded_mobile_token'))) {
                    $validator->errors()->add('verified', __('messages.invalid_mobile_token'));
                }

                $user = UserProfile::where(
                    [
                        ['companies_id', $company->id],
                        ['email', $request->get('username')],
                    ]
                )->first();

                if ($user && !$user->isVerified()) {
                    $validator->errors()->add('verified', __('messages.user-not-verified'));
                }
            }
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

//        return LibUserProfiles::verifyCreateRequest($request, $company ?? null);
        $email = $request->get('username');
        $password = $request->get('password');
        //Compatibilidad a crecion de perfil
        $request->request->add(['email' => $email]);

        $profile = LibUsers::createProfileByEmailAndCompany($request, $email, $company, $password);
        $profile->token = null;

        if ($request->filled('custom_fields')) {
            LibCatalogsTable::processSaveInModel(
                $profile,
                'register',
                $request->get('custom_fields', []),
                $request->file('custom_fields', []),
                $company->id
            );
        }

        return response()->json(['url' => $company->mailWelcomeInfo->correct_url ?? null]);
//        return response()->json($profile);
    }

    public function update(Request $request, $user)
    {
        $profile = UserProfile::find($user);
        if (!$profile) {
            abort(404, __('api-user.NotFound'));
        }

        $company = LibRoute::getCompany($request);

        if (
            $request->get('password') !==
            $request->get('password_confirmation')
        ) {
            throw new ValidationException(
                Validator::make(
                    [
                        'password'              => $request->get('password'),
                        'password_confirmation' => $request->get('password_confirmation'),
                    ],
                    [
                        'password' => 'same:password_confirmation',
                    ]
                )
            );
        }

        //Update request
        $options = [
            'id'           => $profile->id,
            'users_id'     => $profile->users_id,
            'email'        => $profile->email,
            'status'       => 'on',
            'companies_id' => $company->id,
            'filters'      => [
                [
                    'name'  => 'comp_id',
                    'value' => $company->id,
                ],
            ],
        ];
        request()->merge($options);
        $request = request();

        $profileUpdated = CatalogFacade::save($request, CatalogUserProfile::class, $profile->profileCatalog);

        return response()->json($profileUpdated);
    }

    public function delete(Request $request, $user)
    {
        $profile = UserProfile::find($user);
        if (!$user) {
            abort(404, __('api-user.NotFound'));
        }

        $request->merge(['id' => $profile->id]);
        $updated = CatalogFacade::delete($request, CatalogUserProfile::class);

        return response()->json($updated);
    }


    public function restore(Request $request, $id)
    {
        $user = UserProfile::withTrashed()->find($id);
        $user->restore();

        return response()->json($user);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMe()
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        $profile = $user->getProfileInThisCompany();

        return response()->json($profile);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function putMe(Request $request)
    {
        $user = Auth::user();
        $profile = $user->getProfileInThisCompany();
        $company = LibRoute::getCompany($request);

        if (
            $request->get('password') !==
            $request->get('password_confirmation')
        ) {
            throw new ValidationException(
                Validator::make(
                    [
                        'password'              => $request->get('password'),
                        'password_confirmation' => $request->get('password_confirmation'),
                    ],
                    [
                        'password' => 'same:password_confirmation',
                    ]
                )
            );
        }
        //Update request
        $options = [
            'id'           => $profile->id,
            'users_id'     => $user->id,
            'email'        => $profile->email,
            'status'       => 'on',
            'companies_id' => $company->id,
            'filters'      => [
                [
                    'name'  => 'comp_id',
                    'value' => $company->id,
                ],
            ],
        ];
        request()->merge($options);
        $request = request();

        $profileUpdated = CatalogFacade::save($request, CatalogUserProfile::class, $profile->profileCatalog);

        return response()->json($profileUpdated);
    }

    /**
     * Logout
     */
    public function logout()
    {
        $user = Auth::user();

        $accessToken = $user->token();
        DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $accessToken->id)
            ->update(
                [
                    'revoked' => true,
                ]
            );

        $accessToken->revoke();

        return response()->json('ok', 204);
    }

    /**
     * @param Request     $request
     * @param             $locationToSee
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userPurchaseInLocation(Request $request, $locationToSee)
    {
        $user = Auth::user();
        $profile = $user->getProfileInThisCompany();

        \request()->merge(
            [
                'filters' => [
                    [
                        'name'  => 'profile',
                        'value' => $profile,
                    ],
                    [
                        'name'  => 'locations_id',
                        'value' => $locationToSee,
                    ],
                    [
                        'name'  => 'status',
                        'value' => 'complete',
                    ],
                ],
            ]
        );
        $response = CatalogFacade::index($request, CatalogPurchase::class, $request->get('per_page', null));

        return response()->json($response->getRespuestas());
    }

    /**
     * @param Request $request
     * @param Brand   $brand
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userPurchaseInBrand(Request $request, Brand $brand)
    {
        $user = Auth::user();
        $profile = $user->getProfileInThisCompany();

        \request()->merge(
            [
                'filters' => [
                    [
                        'name'  => 'profile',
                        'value' => $profile,
                    ],
                    [
                        'name'  => 'brands_id',
                        'value' => $brand->id,
                    ],
                    [
                        'name'  => 'status',
                        'value' => 'complete',
                    ],
                ],
            ]
        );
        $response = CatalogFacade::index($request, CatalogPurchase::class, $request->get('per_page', null));

        return response()->json($response->getRespuestas());
    }

    /**
     * @param Request $request
     * @param Brand   $brand
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reservationsPastInBrand(Request $request, Brand $brand)
    {
        $user = Auth::user();
        $profile = $user->getProfileInThisCompany();

        \request()->merge(
            [
                'filters' => [
                    [
                        'name'  => 'profile',
                        'value' => $profile,
                    ],
                    [
                        'name'  => 'brands_id',
                        'value' => $brand->id,
                    ],
                    [
                        'name'  => 'reducePopulation',
                        'value' => $request->get('reducePopulation', false) === 'true',
                    ],
                ],
            ]
        );
        $response = CatalogFacade::index($request, CatalogReservations::class, $request->get('per_page', null));

        return response()->json($response->getRespuestas());
    }

    /**
     * @param Request $request
     * @param Brand   $brand
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reservationsFutureInBrand(Request $request, Brand $brand)
    {
        $user = Auth::user();
        $profile = $user->getProfileInThisCompany();
        $reducePopulation = $request->get('reducePopulation', false) === 'true';
        $reservations = LibReservation::userFuturesReservations($profile, $brand);
        $reservations->load(
            [
                'meetings',
                'credit',
                'user',
                'location',
                'waitlist',
            ]
        );
        if ($reducePopulation) {
            $reservations->load(
                [
                    'staff'           => function ($query) {
                        $query->select(
                            [
                                'id',
                                'name',
                            ]
                        );
                    },
                    'service'         => function ($query) {
                        $query->select(
                            [
                                'id',
                                'name',
                            ]
                        );
                    },
                    'room'            => function ($query) {
                        $query->select(
                            [
                                'id',
                                'name',
                            ]
                        );
                    },
                    'user',
                    'location'        => function ($query) {
                        $query->select(
                            [
                                'id',
                                'slug',
                                'name',
                            ]
                        );
                    },
                    'user_membership' => function ($query) {
                        $query->select(
                            [
                                'id',
                                'memberships_id',
                                'expiration_date',
                                'user_profiles_id',
                                'reservations_limit',
                            ]
                        )->with(
                            [
                                'membership' => function ($query) {
                                    $query->select(
                                        [
                                            'id',
                                            'name',
                                            'pic',
                                        ]
                                    );
                                },
                            ]
                        );
                    },
                ]
            );
        }

        $reservations->map(
            function ($reservation) {
                $reservation->canBeCancelled = $reservation->canBeCancelled();

                return $reservation;
            }
        );


        return response()->json($reservations);
    }

    /**
     * @param Request     $request
     * @param Brand       $brand
     * @param Reservation $reservation
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReservation(Request $request, Brand $brand, Reservation $reservation)
    {
        $user = Auth::user();
        $profile = $user->getProfileInThisCompany();
        if (
            ($reservation->user_profiles_id) !== ((int)$profile->id)
            ||
            $reservation->brands_id !== $brand->id
        ) {
            abort(400);
        }

        $reservation->load(
            [
                'meetings',
                'credit',
                'staff'           => function ($query) {
                    $query->select(
                        [
                            'id',
                            'name',
                        ]
                    );
                },
                'service'         => function ($query) {
                    $query->select(
                        [
                            'id',
                            'name',
                        ]
                    );
                },
                'user',
                'location'        => function ($query) {
                    $query->select(
                        [
                            'id',
                            'slug',
                            'name',
                        ]
                    );
                },
                'user_membership' => function ($query) {
                    $query->select(
                        [
                            'id',
                            'memberships_id',
                            'expiration_date',
                            'reservations_limit',
                        ]
                    )->with(
                        [
                            'membership' => function ($query) {
                                $query->select(
                                    [
                                        'id',
                                        'name',
                                        'pic',
                                    ]
                                );
                            },
                        ]
                    );
                },
                'waitlist',
            ]
        );

        return response()->json($reservation);
    }

    /**
     * Recibe un token y llama a la función 'verifyToken' y redirige a la url
     * especificada en mails_welcome, correct_url o incorrect_url.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function verifyUser(Request $request)
    {
        if (!$request->has('token') || is_null($request->get('token'))) {
            abort(400);
        }

        $this->validate(
            $request,
            [
                'email' => 'required|email|unique:users',
            ],
            [
                'email' => __('messages.user-email-missing'),
            ]
        );

        $response = LibUserProfiles::verifyToken($request->get('token'), $request->get('email'));

        $header_name = $response->getVerified() ? 'GAFAFIT-MESSAGE' : 'GAFAFIT-ERROR-MESSAGE';

        return redirect($response->getUrl())->header($header_name, $response->getMessage());
    }

    /**
     * @param Request $request
     *
     * @param Brand   $brand
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function credits(Request $request, Brand $brand)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        $profile = $user->getProfileInThisCompany();

        return response()->json(
            $profile->allCreditsCountWithValidityInfoInBrand($brand, false, 0, '>', UsersCredits::class, false)
                ->with(
                    [
                        'credit.services',
                    ]
                )
                ->addSelect(
                    [
                        DB::raw('ANY_VALUE(expiration_date) as expiration_date'),
                    ]
                )
                ->get()
        );
    }

    /**
     * @param Request $request
     * @param Brand   $brand
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function memberships(Request $request, Brand $brand)
    {
        $user = Auth::user();
        $profile = $user->getProfileInThisCompany();

        return response()->json(
            $profile->allMembershipsWithValidityInfoInBrand($brand, false)->with('membership')->get()
        );
    }

    /**
     * @param Request     $request
     * @param Brand       $brand
     * @param Reservation $reservation
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reservationCancel(Request $request, Brand $brand, Reservation $reservation)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        $profile = $user->getProfileInThisCompany();
        if (!$reservation->canBeCancelled()) {
            abort(403, __('reservations.errors.canNotCancell'));
        } else {
            if (
                $profile
                &&
                $reservation->user_profiles_id === $profile->id
            ) {
                $reservation->cancel();
            } else {
                abort(403, __('reservations.errors.notUser'));
            }
        }

        return response()->json($reservation);
    }

    public function resendVerificationEmail(Request $request)
    {
        if (!$request->has('company') || !$request->has('email') || !$request->get('email')) {
            abort(403);
        }

        $email = $request->get('email');
        $companies_id = $request->get('company');
        $company = Company::find($companies_id);
        if (!$company) {
            abort(404);
        }
        $response = LibUserProfiles::resendVerificationEmail($email, $company);

        if ($response->getUrl()) {
            $header_name = $response->getVerified() ? 'GAFAFIT-MESSAGE' : 'GAFAFIT-MESSAGE';

            return redirect($response->getUrl())->header($header_name, $response->getMessage());
        } else {
            return response()->json($response->getMessage());
        }
    }

    /**
     * @param Request  $request
     * @param Brand    $brand
     * @param Purchase $purchase
     *
     * @return Purchase
     */
    public function userPurchase(Request $request, Brand $brand, Purchase $purchase)
    {
        $user = Auth::user()->getProfileInThisCompany();
        if ($user->id !== $purchase->user_profiles_id) {
            abort(401);
        }

        if ($purchase->companies_id !== $this->getCompany()->id || $purchase->brands_id !== $brand->id) {
            abort(401);
        }

        $purchase->load(['giftCard', 'items', 'discountCode']);

        return $purchase;
    }

    /**
     * Waitlist futuras
     *
     * @param Request $request
     * @param Brand   $brand
     *
     * @return mixed
     */
    public function waitlistFutureInBrand(Request $request, Brand $brand)
    {
        $user = Auth::user()->getProfileInThisCompany();
        $reducePopulation = $request->get('reducePopulation', false) === 'true';

        $waitlist = $user->waitlist()->where(
            [
                ['meeting_start', '>', $brand->now()],
                ['status', '!=', 'returned'],
            ]
        )->with(
            [
                'meetings',
                'credit',
                'user',
                'location',
            ]
        )->get();

        if ($reducePopulation) {
            $waitlist->load(
                [
                    'staff'           => function ($query) {
                        $query->select(
                            [
                                'id',
                                'name',
                            ]
                        );
                    },
                    'service'         => function ($query) {
                        $query->select(
                            [
                                'id',
                                'name',
                            ]
                        );
                    },
                    'user',
                    'location'        => function ($query) {
                        $query->select(
                            [
                                'id',
                                'slug',
                                'name',
                            ]
                        );
                    },
                    'user_membership' => function ($query) {
                        $query->select(
                            [
                                'id',
                                'memberships_id',
                                'expiration_date',
                                'reservations_limit',
                            ]
                        )->with(
                            [
                                'membership' => function ($query) {
                                    $query->select(
                                        [
                                            'id',
                                            'name',
                                            'pic',
                                        ]
                                    );
                                },
                            ]
                        );
                    },
                ]
            );
        }

        return $waitlist;
    }

    /**
     * Waitlist pasadas
     *
     * @param Request $request
     * @param Brand   $brand
     *
     * @return mixed
     */
    public function waitlistPastInBrand(Request $request, Brand $brand)
    {
        $user = Auth::user()->getProfileInThisCompany();
        $reducePopulation = $request->get('reducePopulation', false) === 'true';

        $waitlist = $user->waitlist()->where(
            [
                ['meeting_start', '<', $brand->now()],
                ['status', '!=', 'waiting'],
            ]
        )->with(
            [
                'meetings',
                'credit',
                'user',
                'location',
            ]
        )->get();

        if ($reducePopulation) {
            $waitlist->load(
                [
                    'staff'           => function ($query) {
                        $query->select(
                            [
                                'id',
                                'name',
                            ]
                        );
                    },
                    'service'         => function ($query) {
                        $query->select(
                            [
                                'id',
                                'name',
                            ]
                        );
                    },
                    'user',
                    'location'        => function ($query) {
                        $query->select(
                            [
                                'id',
                                'slug',
                                'name',
                            ]
                        );
                    },
                    'user_membership' => function ($query) {
                        $query->select(
                            [
                                'id',
                                'memberships_id',
                                'expiration_date',
                                'reservations_limit',
                            ]
                        )->with(
                            [
                                'membership' => function ($query) {
                                    $query->select(
                                        [
                                            'id',
                                            'name',
                                            'pic',
                                        ]
                                    );
                                },
                            ]
                        );
                    },
                ]
            );
        }

        return $waitlist;
    }

    /**
     * @param Request $request
     * @param Brand   $brand
     *
     * @return mixed
     */
    public function subscriptionsList(Request $request, Brand $brand)
    {
        $user = Auth::user()->getProfileInThisCompany();

        $only_actives = $request->input('only_actives');

        return $user->subscriptions()->when(
            $only_actives,
            function ($q) {
                return $q->where('status', 'active');
            }
        )->get();
    }

    /**
     * @param Request      $request
     * @param Brand        $brand
     * @param Subscription $subscription
     *
     * @return Subscription
     */
    public function subscriptionCancel(Request $request, Brand $brand, Subscription $subscription)
    {
        $user = Auth::user()->getProfileInThisCompany();

        if ($subscription->users_profiles_id !== $user->id) {
            throw new NotFoundHttpException();
        }

        $cancelGafapay = LibGafaPay::cancelSubscription($subscription->subscription_id_gafapay);
        if ($cancelGafapay)
            $subscription->cancel();
        else
            throw new NotFoundHttpException();

        return $subscription;
    }
}
