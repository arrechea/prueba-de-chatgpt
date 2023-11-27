<?php

namespace App\Librerias\Reservation;

use App\Exceptions\FancyException;
use App\Librerias\DiscountCode\LibDiscountCode;
use App\Librerias\Payments\LibPayments;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Admin\AdminProfile;
use App\Models\Brand\Brand;
use App\Models\Combos\Combos;
use App\Models\Products\Product;
use App\Models\Company\Company;
use App\Models\Countries;
use App\Models\Location\Location;
use App\Models\Meeting\Meeting;
use App\Models\Membership\Membership;
use App\Models\Purchase\PurchaseGiftCard;
use App\Models\Service;
use App\Models\User\UserProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Collection as CollectionDB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator as ValidatorFacade;
use Illuminate\Validation\Rule;
use stdClass;
use Validator;

/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 21/05/18
 * Time: 15:45
 */
class LibReservationForm
{
    /**
     * @var UserProfile|null
     */
    private $userProfile;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var Meeting
     */
    private $meeting;

    /**
     * @var AdminProfile
     */
    private $admin;
    /**
     * @var bool
     */
    private $isExternalApp = false;
    /**
     * @var Combos
     */
    private $combo;
    /**
     * @var Products
     */
    private $product;
    /**
     * @var Location
     */
    private $location;
    /**
     * @var Membership
     */
    private $membership;
    /**
     * @var bool
     */
    private $inAdmin = false;

    /**
     * LibReservationForm constructor.
     *
     * @param Request     $request
     * @param UserProfile $userProfile
     * @param Location    $location
     */
    private function __construct(Request $request, UserProfile $userProfile = null, Location $location)
    {
        $this->request = $request;
        $this->userProfile = $userProfile;
        $this->location = $location;
    }

    /**
     * @return bool
     */
    public function isExternalApp(): bool
    {
        return $this->isExternalApp;
    }

    /**
     * @param bool $isExternalApp
     */
    public function setIsExternalApp(bool $isExternalApp)
    {
        $this->isExternalApp = $isExternalApp;
    }

    /**
     * @return bool
     */
    private function requestAcceptsJSON(): bool
    {
        return $this->getRequest()->get('json', false) === 'true';
    }

    /**
     * Get Form Template
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getFormTemplate()
    {
        $user = $this->getUser();
        if ($user) {
            $user->reservations_count = $user->reservations()->count();
        }

        $admin = $this->getAdmin();
        $location = $this->getLocation();
        $location->load([
            'brand.payment_types',
            'company.client',
        ]);
        $validCredits = null;
        $validMemberships = null;

        $meeting = $this->getMeeting();
        $necessaryCredits = null;
        $service = null;

        $paymentTypes = $location->brand->validPaymentTypes(($admin ? true : false));

        if ($meeting) {
            /**
             * @var Service $service
             */
            $service = $meeting->service;
            $necessaryCredits = $service->neccesaryCredits();
            $reservationsUserInMeeting = $user ? $meeting->reservation()->where('user_profiles_id', $user->id)->count() : 0;

            //Se quiere reservar meeting
            $validCredits = $user ? $user->validCreditsForServiceInLocation($location, $service) : new Collection();

            //tener en cuenta el maximo de reservas por meeting
            $validMemberships = $user ? $user->validMembershipsForServiceInLocation($location, $service)->filter(function ($userMembership) use ($reservationsUserInMeeting, $user, $meeting) {
                //Filtro de membresias

                return $user->canUseUserMembershipInMeeting($userMembership, $meeting);
            })->values()
                : new Collection();

            if ($meeting->reserve_auto && $meeting->recurring_meeting_id != null) {
                $meetings = Meeting::where('recurring_meeting_id', $meeting->recurring_meeting_id)
                    ->withCount('reservation')
                    ->get();

                foreach ($validCredits as $c_key => $c_item) {
                    if ($c_item->total < count($meetings)) {
                        $validCredits->forget($c_key);
                    }
                }

                foreach ($validMemberships as $m_key => $m_item) {
                    if ($m_item->reservations_limit < count($meetings)) {
                        $validMemberships->forget($m_key);
                    }
                }
            }

            //Mostramos solo los combos y memberships que necesitamos
            $combosSelection = $location->getCombosForService($service, $user);

            $membershipSelection = $location->getMembershipsForService($service, $user);
        } else {
            //No se quiere reservar meeting
            //Mostramos todos los combos y memberships del location
            $combosSelection = $location->getCombos(true, $user);
            $membershipSelection = $location->getMemberships(true, $user);
        }
        //Filtrar membershipSelection
        $userMembershipsIds = $user ? $user->allMembershipsWithValidityInfoInLocation($location)->map(function ($userMemebership) {
            return $userMemebership->memberships_id;
        }) : new Collection();
        if ($membershipSelection && $userMembershipsIds->count() > 0) {
            $membershipSelection = $membershipSelection->filter(function ($posibleMembership) use ($userMembershipsIds) {
                return !in_array($posibleMembership->id, $userMembershipsIds->toArray());
            })->values();
        }

        $membershipSelection->load('credits');

        $isAdmin = $this->isInAdmin();
        $isExternalApp = $this->isExternalApp();

        //UrlReservation
        $getFancyUrls = $this->getFancyUrls($location, $user, $isAdmin, $isExternalApp);

        $products = Product::where('brands_id', '=', $location->brand->id)->where('companies_id', '=', $location->company->id)->get();

        $default_store_tab = $this->getRequest()->get('default_store_tab', null);

        return VistasGafaFit::view('reservation.template', [
            //user
            'user'                     => $user,//user
            'user_Credits'             => $user ? $user->allCreditsCountWithValidityInfoInLocation($location) : new Collection(),//all credits in location
            'user_ValidCredits'        => $validCredits,//valid credits for purchase
            'user_ValidMembership'     => $validMemberships,
            'user_waivers'             => $user ? $user->allWaiversInBrand($location->brand) : new Collection(),
            //admin
            'admin'                    => $admin,//admin, he do the purchase in admin
            //general
            'location'                 => $location,//location
            'currency'                 => $location->brand->currency,
            'countries'                => Countries::select('id', 'name')->get(),
            //meeting
            'meeting'                  => $meeting,//if user want to reserve in a meeting
            'meeting_neccesaryCredits' => $necessaryCredits,
            //products
            'combo'                    => $this->getCombo(),//if user select combo to purchase
            'product'                  => $products,
            'membership'               => $this->getMembership(),//if user select membership to purchase
            'combosSelection'          => $combosSelection,
            'membershipSelection'      => $membershipSelection,
            //payments
            'payment_types'            => $paymentTypes,
            'payment_info_userProfile' => $this->getPaymentUserInfoInBrand($paymentTypes),
            //Sytem
            'urlReservation'           => $getFancyUrls['urlReservation'],
            'urlGenerateCode'          => $getFancyUrls['urlGenerateCode'],
            'urlCheckGiftCode'         => $getFancyUrls['urlCheckGiftCode'],
            'urlCheckDiscountCode'     => $getFancyUrls['urlCheckDiscountCode'],
            'csrf'                     => csrf_token(),
            'bearer'                   => $admin ? null : $this->getBearer(),
            'langFile'                 => new CollectionDB(Lang::get('reservation-fancy')),
            'recurrent_payment'        => $user ? $user->recurrent_payment()->with('payment_type')->first() : null,
            'gafapay'                  => ['client_id' => $location->brand->gafapay_client_id, 'client_secret' => $location->brand->gafapay_client_secret],
            'isExternalApp'            => $isExternalApp,
            'title'                    => 'Checkout',
            'requestMap'               => $this->mapRequestForViews(),
            'tokenMovil'               => env('HARD_CODED_MOBILE_TOKEN', ''),
            'default_store_tab'        => $default_store_tab,
        ],
            [],
            request()->get('forcejson', false) === 'on'
        );
    }

    /**
     * @param Location         $location
     * @param UserProfile|null $user
     * @param bool             $isAdmin
     * @param bool             $isExternalApp
     *
     * @return array
     */
    private function getFancyUrls(Location $location, UserProfile $user = null, bool $isAdmin, bool $isExternalApp): array
    {
        $urlReservation = $isAdmin ?
            route(
                'admin.company.brand.locations.reservations.reservate', [
                    'company'  => $location->company,
                    'brand'    => $location->brand,
                    'location' => $location,
                ]
            ) : (
            $isExternalApp ? route(
                'cosmics.reservate', [
                    'brand'    => $location->brand,
                    'location' => $location,
                    'user'     => $user,
                ]
            ) :
                route(
                    'api.brand.location.reservation.reservate', [
                        'brand'         => $location->brand,
                        'locationToSee' => $location,
                    ]
                )
            );

        $urlGenerateCode = $isAdmin ?
            route(
                'admin.company.brand.locations.reservations.generate-gift-code', [
                    'company'  => $location->company,
                    'brand'    => $location->brand,
                    'location' => $location,
                ]
            ) : (
            $isExternalApp ? route(
                'cosmics.generate-gift-code', [
                    'brand'    => $location->brand,
                    'location' => $location,
                    'user'     => $user,
                ]
            ) :
                route(
                    'api.brand.location.reservation.generate-gift-code', [
                        'brand'         => $location->brand,
                        'locationToSee' => $location,
                    ]
                )
            );

        $urlCheckGiftCode = $isAdmin ?
            route(
                'admin.company.brand.locations.reservations.check-gift-code', [
                    'company'  => $location->company,
                    'brand'    => $location->brand,
                    'location' => $location,
                    'code'     => '_|_',
                ]
            ) :
            (
            $isExternalApp ? route(
                'cosmics.check-gift-code', [
                    'brand'    => $location->brand,
                    'location' => $location,
                    'user'     => $user,
                    'code'     => '_|_',
                ]
            ) :
                route(
                    'api.brand.location.reservation.check-gift-code', [
                        'brand'         => $location->brand,
                        'locationToSee' => $location,
                        'code'          => '_|_',
                    ]
                )
            );

        $urlCheckDiscountCode = $isAdmin ?
            route(
                'admin.company.brand.locations.reservations.check-discount-code', [
                    'company'      => $location->company,
                    'brand'        => $location->brand,
                    'location'     => $location,
                    'user_profile' => $user,
                    'code'         => '_|_',
                ]
            ) :
            (
            $isExternalApp ? route(
                'cosmics.check-discount-code', [
                    'brand'        => $location->brand,
                    'location'     => $location,
                    'user'         => $user,
                    'user_profile' => $user,
                    'code'         => '_|_',
                ]
            ) :
                route(
                    'api.brand.location.reservation.check-discount-code', [
                        'brand'         => $location->brand,
                        'locationToSee' => $location,
                        'user_profile'  => $user,
                        'code'          => '_|_',
                    ]
                )
            );

        return [
            'urlReservation'       => $urlReservation,
            'urlGenerateCode'      => $urlGenerateCode,
            'urlCheckGiftCode'     => $urlCheckGiftCode,
            'urlCheckDiscountCode' => $urlCheckDiscountCode,
        ];
    }

    /**
     * @param       $paymentTypes
     *
     * @return Collection
     */
    private
    function getPaymentUserInfoInBrand($paymentTypes): Collection
    {
        $user = $this->getUser();
        $brand = ($this->getLocation())->brand;

        return $user ? LibPayments::getPaymentUserInfoInBrand($user, $brand, $paymentTypes) : new Collection();
    }

    /**
     * @return mixed
     */
    private
    function getBearer()
    {
        return $this->getRequest()->header('Authorization');
    }

    /**
     * Validation preForm
     *
     * @param Request  $request
     * @param Company  $company
     * @param Location $location
     *
     * @return \Illuminate\Validation\Validator
     * @throws \Throwable
     */
    static public function validateRequest(Request $request, Company $company, Location $location)
    {
        /**
         * @var \Illuminate\Validation\Validator $validator
         */
        $validator = Validator::make($request->all(), [
            'users_id'       => [
                'nullable',
                Rule::exists('user_profiles', 'id')
                    ->where(function ($query) use ($company) {
                        $query->where('status', 'active');
                        $query->where('companies_id', $company->id);
                    }),
            ],
            'combos_id'      => [
                'nullable',
                Rule::exists('combos', 'id')
                    ->where(function ($query) use ($company) {
                        $query->where('status', 'active');
                        $query->where('companies_id', $company->id);
                    }),
            ],
            'memberships_id' => [
                'nullable',
                Rule::exists('memberships', 'id')
                    ->where(function ($query) use ($company) {
                        $query->where('status', 'active');
                        $query->where('companies_id', $company->id);
                    }),
            ],
        ], [
            'users_id.exists'       => __('messages.reservation-users_id'),
            'combos_id.exists'      => __('messages.reservation-combos_id'),
            'memberships_id.passed' => __('messages.reservation-memberships_id'),
        ]);
        $validator->after(function ($val) use ($location, $request) {
            if ($request->has('meetings_id')) {
                $meeting = Meeting::find($request->get('meetings_id'));
                if (!$meeting || $meeting->locations_id !== $location->id) {
                    $val->errors()->add('meetings_id.exists', __('messages.reservation-meetings_id-exists'));
//                } else if ($meeting->isEnd()) {
//                    $val->errors()->add('meetings_id.passed', __('messages.reservation-meetings_id'));
                } else if (!$meeting->has('room') || !$meeting->room->isActive()) {
                    $val->errors()->add('room', __('messages.reservation-room'));
                }
            }
        });

        if ($validator->fails()) {
            LibReservationForm::throwError($validator);
        }

        return $validator;
    }

    /**
     * @param ValidatorFacade $validator
     *
     * @param string          $message
     * @param int             $code
     *
     * @throws FancyException
     */
    static private function throwError(ValidatorFacade $validator, $message = "", $code = 0)
    {
        throw new FancyException($validator, $message, $code);
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Location $location
     * @param bool     $isAdmin
     * @param bool     $isExternalApp
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    static public function generateForm(
        Request  $request,
        Company  $company,
        Location $location,
        bool     $isAdmin,
        bool     $isExternalApp = false
    )
    {
        if (!$isAdmin) {
            $language = $location->brand->language->slug;
            Lang::setLocale($language);
        }

        //Validate
        $validator = LibReservationForm::validateRequest($request, $company, $location);

        //Get variables
        $admin = null;
        /**
         * @var Meeting|null $meeting
         */
        $meeting = null;
        $combo = null;
        $membership = null;

        if ($isAdmin) {
            //Es administrador
            $admin = Auth::user()->getProfileInThisCompany();
            $user = UserProfile::where('id', $request->get('users_id'))
                ->where('companies_id', $company->id)
                ->first();
        } else {
            //No es administrador
            $userAuth = Auth::user();
            $user = $userAuth ? Auth::user()->getProfileInThisCompany($location) : null;
            unset($userAuth);
        }

        if ($request->has('meetings_id')) {
            $meeting = Meeting::where('id', $request->get('meetings_id'))
                ->with([
                    'staff',
                    'service',
                    'map'         => function ($q) {
                        $q->select([
                            'id',
                            'name',
                            'image_background',
                            'rows',
                            'columns',
                        ])->with([
                            'objects' => function ($q) {
                                $q->with([
                                    'positions' => function ($q) {
                                        $q->select([
                                            'id',
                                            'name',
                                            'type',
                                            'height',
                                            'width',
                                            'image',
                                            'image_disabled',
                                            'image_selected',
                                        ]);
                                    },
                                ]);
                            },
                        ]);
                    },
                    'reservation' => function ($q) {
                        $q->select([
                            'maps_objects_id',
                            'meetings_id',
                        ])->with([
                            'object' => function ($q) {
                                $q->with([
                                    'positions' => function ($q) {
                                        $q->select([
                                            'id',
                                            'name',
                                            'type',
                                            'height',
                                            'width',
                                            'image',
                                            'image_disabled',
                                            'image_selected',
                                        ]);
                                    },
                                ]);
                            },
                        ]);
                    },
                ])
                ->where('locations_id', $location->id)
                ->first();

            $validator->after(function ($validator) use ($meeting, $isAdmin, $user) {
                $canAcceptPeople = $meeting->canAcceptPeople($isAdmin, $validator);
                $val = Validator::make([], []);
                if (!$meeting->canAcceptReservations($val, $isAdmin) && $canAcceptPeople) {
                    //Comprobamos si el usuario ya no tiene un compromiso previo
                    if ($user->hasSomeKindOfReservationInSameTime($meeting)) {
                        $validator->errors()->add('user', __('reservation-fancy.error.isFull'));
                    }
                }
            });
        }

        if ($request->has('combos_id')) {
            $combo = Combos::where('id', $request->get('combos_id'))
                ->where('brands_id', $location->brand->id)
                ->where('status', 'active')
                ->first();
        }
        if ($request->has('memberships_id')) {
            $membership = Membership::where('id', $request->get('memberships_id'))
                ->where('brands_id', $location->brand->id)
                ->where('status', 'active')
                ->first();
        }
        if ($combo || $membership) {
            $validator->after(function ($validator) use (
                $user,
                $combo,
                $membership
            ) {
                if ($combo) {
                    if (!$user->canBuy($combo)) {
                        $validator->errors()->add('user', __('reservation-fancy.error.product.limit'));
                    }
                    if (!$combo->isValidForUserCategories($user)) {
                        $validator->errors()->add('user', __('reservation-fancy.error.product.restricted'));
                    }
                }
                if ($membership) {
                    if (!$user->canBuy($membership)) {
                        $validator->errors()->add('user', __('reservation-fancy.error.product.limit'));
                    }
                    if (!$membership->isValidForUserCategories($user)) {
                        $validator->errors()->add('user', __('reservation-fancy.error.product.restricted'));
                    }
                }
            });
        }

        if ($validator->fails()) {
            LibReservationForm::throwError($validator);

        }

        //Set variables
        $form = new LibReservationForm($request, $user, $location);
        if ($meeting) {
            $form->setMeeting($meeting);
        }
        if ($combo) {
            if ($user->canBuy($combo)) {
                $form->setCombo($combo);
            }
        }
        if ($membership) {
            if ($user->canBuy($membership)) {
                if ($meeting) {
                    if (!$user->canUseMembershipInMeeting($membership, $meeting)) {
                        $validator->after(function ($validator) use ($meeting) {
                            $validator->errors()->add('meetings_id', __('reservation-fancy.error.membershipLimit'));
                        });
                    } else {
                        //compra con meeting
                        $form->setMembership($membership);
                    }
                } else {
                    //compra individual
                    $form->setMembership($membership);
                }
            }
        }
        if ($isAdmin && $admin) {
            $form->setAdmin($admin);
            $form->setInAdmin(true);
        } else if ($isExternalApp) {
            $form->setIsExternalApp($isExternalApp);
        }
        //Validate
        if ($validator->fails()) {
            LibReservationForm::throwError($validator);
        }

        return $form->getFormTemplate();
    }

    /**
     * This functions serves to send Data to de view
     *
     * @return Collection
     */
    public function mapRequestForViews(): Collection
    {
        $request = $this->getRequest();

        return new Collection([
            'url'  => $request->getSchemeAndHttpHost() . $request->getPathInfo(),
            'vars' => $request->all(),
        ]);
    }

    /**
     * @param Company $company
     * @param Brand   $brand
     *
     * @return JsonResponse
     */
    static public function generateGiftCode(Company $company = null, Brand $brand = null, $attemp = 1): JsonResponse
    {
        $prefix = 'GafaFit';
        $micro = microtime();
        if ($brand) {
            $prefix = "Brand-{$brand->id}";
        } else if ($company) {
            $prefix = "Company-{$company->id}";
        }
        $code = md5("$prefix-$micro");

        if (!self::isGiftCodeValidToGenerate($code, $brand)) {
            if ($attemp < 5) {
                //Posibilidad de loop
                $attemp++;

                return self::generateGiftCode($company, $brand, $attemp);
            } else {
                abort(500);
            }
        }

        return response()->json($code);
    }

    /**
     * @param string $code
     * @param Brand  $brand
     *
     * @return bool
     */
    static public function isGiftCodeValidToGenerate(string $code, Brand $brand): bool
    {
        $codeUsedYet = PurchaseGiftCard::where('code', $code)
            ->where('brands_id', $brand->id)
            ->where('is_active', 1)
            ->count();

        return $codeUsedYet === 0;
    }

    /**
     * @param string      $code
     * @param Brand       $brand
     *
     * @param UserProfile $userProfile
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    static public function responseDiscountCodeValid(string $code, Brand $brand, UserProfile $userProfile, Request $request): JsonResponse
    {
        $combo = Combos::find($request->input('combo'));
        $membership = Membership::find($request->input('membership'));
        $product = Product::find($request->input('product'));

        if ($combo) {
            $purchase_item = $combo;
        } else if ($membership) {
            $purchase_item = $membership;
        } else if ($product) {
            $purchase_item = $product;
        } else {
            return response()->json(__('reservation-fancy.error.discount_code.no_item'));
        }

        \request()->merge([
            'json' => 'true',
        ]);
        $discountCode = LibDiscountCode::checkoutDiscountCodeValid($code, $brand, $userProfile, $purchase_item);

        if ($discountCode) {
            return response()->json($discountCode, 200);
        } else {
            return response()->json(__('reservation-fancy.error.discount_code.unknown'), 409);
        }
    }

    /**
     * @return UserProfile
     */
    public function getUser(): ?UserProfile
    {
        return $this->userProfile;
    }

    /**
     * @return Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * @return Meeting|null
     */
    public function getMeeting(): ?Meeting
    {
        return $this->meeting;
    }

    /**
     * @param Meeting $meeting
     */
    private function setMeeting(Meeting $meeting)
    {
        $this->meeting = $meeting;
    }

    /**
     * @return AdminProfile|null
     */
    public function getAdmin(): ?AdminProfile
    {
        return $this->admin;
    }

    /**
     * @param AdminProfile $admin
     */
    private function setAdmin(AdminProfile $admin)
    {
        $this->admin = $admin;
    }

    /**
     * @return Combos|null
     */
    public function getCombo(): ?Combos
    {
        return $this->combo;
    }

    /**
     * @param Combos $combo
     */
    private function setCombo(Combos $combo)
    {
        $this->combo = $combo;
    }

    public function getProduct(): ?Combos
    {
        return $this->product;
    }

    /**
     * @param Combos $product
     *
     * @internal param Combos $combo
     */
    private function setProduct(Combos $product)
    {
        $this->product = $product;
    }

    /**
     * @return Location
     */
    public function getLocation(): Location
    {
        return $this->location;
    }

    /**
     * @param string $namespace
     */
    static public function routes(string $namespace)
    {
        \Route::get('/create-form-template', "$namespace@getFormTemplate")->name('getFormTemplate');
        \Route::get('/create-form', "$namespace@getForm")->name('getForm');
        \Route::post('/reservate', "$namespace@reservate")->name('reservate');
        \Route::get('/generate-gift-code', "$namespace@generateGiftCode")->name('generate-gift-code');
        \Route::get('/check-gift-code/{code}', "$namespace@checkGiftCode")->name('check-gift-code');
        \Route::get('/check-discount-code/{code}/{user_profile}', "$namespace@checkDiscountCode")->name('check-discount-code');
    }

    /**
     * @return bool
     */
    public function isInAdmin(): bool
    {
        return $this->inAdmin;
    }

    /**
     * @param bool $inAdmin
     */
    private function setInAdmin(bool $inAdmin)
    {
        $this->inAdmin = $inAdmin;
    }

    /**
     * @return Membership
     */
    public function getMembership(): ?Membership
    {
        return $this->membership;
    }

    /**
     * @param Membership $membership
     */
    private function setMembership(Membership $membership)
    {
        $this->membership = $membership;
    }
}
