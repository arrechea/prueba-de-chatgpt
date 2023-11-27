<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 03/08/2018
 * Time: 12:40 PM
 */

namespace App\Http\Controllers\Admin\Location;


use App\Admin;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Location\CatalogUserProfile;
use App\Librerias\Catalog\Tables\Location\Metrics\CatalogUserCredits;
use App\Librerias\Catalog\Tables\Location\Metrics\CatalogUserCreditsPast;
use App\Librerias\Catalog\Tables\Location\Metrics\CatalogUserCreditsUsed;
use App\Librerias\Catalog\Tables\Location\Metrics\CatalogUserCreditsDelete;
use App\Librerias\Catalog\Tables\Location\Metrics\CatalogUserPurchase;
use App\Librerias\Catalog\Tables\Location\UserInformation\CatalogUserActiveMemberships;
use App\Librerias\Catalog\Tables\Location\UserInformation\CatalogUserExpiredMemberships;
use App\Librerias\Credits\LibCredits;
use App\Librerias\GafaPay\LibGafaPay;
use App\Librerias\Models\Users\LibUsers;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\UserCredits\LibUserCredits;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Countries;
use App\Models\Credit\CreditsBrand;
use App\Models\Location\Location;
use App\Http\Requests\AdminRequest as Request;
use App\Models\Purchase\Purchasable;
use App\Models\Purchase\Purchase;
use App\Models\User\Subscribable;
use App\Models\User\UserProfile;
use App\Models\User\UsersMemberships;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserLocationController extends LocationLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);
        $location = $this->getLocation();

        $this->middleware(function ($request, $next) use ($location) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::USER_VIEW, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });

        $this->middleware(function ($request, $next) use ($location) {
            $profile = $request->route('user');
            if (!!$profile) {
                if ($profile->id !== (int)$request->get('id')) {
                    throw new NotFoundHttpException();
                }
            }


            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::USER_EDIT, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'save',
        ]);

        $this->middleware(function ($request, $next) use ($location) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::USER_CREATE, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'saveNew',
        ]);

        $this->middleware(function ($request, $next) use ($location) {
            if (!$request->has('companies_id') || (int)$request->get('companies_id') !== $this->getCompany()->id) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'saveNew',
            'save',
        ]);

        $this->middleware(function ($request, $next) use ($location) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::USER_UNBLOCK, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'unblockUser',
        ]);

        $this->middleware(function ($request, $next) {
            if (!$request->has('credits_id') ||
                !$request->input('credits_id') ||
                !$request->has('purchases_id') ||
                !$request->input('purchases_id')
            ) {
                throw new NotFoundHttpException();
            }

            $profile = $request->route('profile');

            if (!$profile->allCredits()->where([
                ['purchases_id', $request->input('purchases_id')],
                ['credits_id', $request->input('credits_id')],
            ])->first()
            ) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'saveCredit',
            'deleteCredit',
        ]);

        $this->middleware(function ($request, $next) use ($location) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::USER_CREDITS_EDIT, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'saveCredit',
        ]);

        $this->middleware(function ($request, $next) use ($location) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::USER_CREDITS_DELETE, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'deleteCredit',
        ]);

        $this->middleware(function ($request, $next) use ($location) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::USER_MEMBERSHIP_DELETE, $location)) {
                throw new NotFoundHttpException();
            }

            $profile = $request->route('profile');

            if (!$profile || !$profile->allMemberships()->find($request->route('membership')->id)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'deleteMembership',
        ]);

        $this->middleware(function ($request, $next) use ($location) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::SUBSCRIPTION_CANCEL, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'cancelSubscription',
        ]);
    }

    public function index(Request $request, Company $company, Brand $brand, Location $location)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogUserProfile::class));
        }

        $catalogo = new CatalogUserProfile();

        return VistasGafaFit::view('admin.location.users.index', [
            'catalogo' => $catalogo,
        ]);

    }

    public function credits(Request $request, Company $company, Brand $brand, Location $location, UserProfile $profile)
    {

        $micro = \App\Librerias\Catalog\LibDatatable::GetTableId();

        $catalogo = new CatalogUserCredits();
        $catalogo1 = new CatalogUserCreditsPast();
        $catalogo2 = new CatalogUserCreditsUsed();
        $catalogo3 = new CatalogUserCreditsDelete();
        $catalogActiveMemberships = new CatalogUserActiveMemberships();
        $catalogExpiredMemberships = new CatalogUserExpiredMemberships();

        $ajaxDatatable3 = route('admin.company.brand.locations.users.ajax.delete', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
            'catalogo' => $catalogo3,
            'profile'  => $profile,
        ]);

        $ajaxDatatable2 = route('admin.company.brand.locations.users.ajax.used', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
            'catalogo' => $catalogo2,
            'profile'  => $profile,
        ]);

        $ajaxDatatable1 = route('admin.company.brand.locations.users.ajax.past', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
            'catalogo' => $catalogo1,
            'profile'  => $profile,
        ]);

        $ajaxDatatable = route('admin.company.brand.locations.users.ajax', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
            'catalogo' => $catalogo,
            'profile'  => $profile,
        ]);

        $ajaxDatatableActiveMemberships = route('admin.company.brand.locations.users.memberships.active', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
            'catalogo' => $catalogo,
            'profile'  => $profile,
        ]);

        $ajaxDatatableExpiredMemberships = route('admin.company.brand.locations.users.memberships.expired', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
            'catalogo' => $catalogo,
            'profile'  => $profile,
        ]);

        return VistasGafaFit::view('admin.location.users.Creditos.activos', [
            'catalogo'                        => $catalogo,
            'catalogo1'                       => $catalogo1,
            'catalogo2'                       => $catalogo2,
            'catalogo3'                       => $catalogo3,
            'catalogActiveMemberships'        => $catalogActiveMemberships,
            'catalogExpiredMemberships'       => $catalogExpiredMemberships,
            'ajaxDatatable'                   => $ajaxDatatable,
            'ajaxDatatable1'                  => $ajaxDatatable1,
            'ajaxDatatable2'                  => $ajaxDatatable2,
            'ajaxDatatable3'                  => $ajaxDatatable3,
            'ajaxDatatableActiveMemberships'  => $ajaxDatatableActiveMemberships,
            'ajaxDatatableExpiredMemberships' => $ajaxDatatableExpiredMemberships,
            'profile'                         => $profile,
            'micro'                           => $micro,

        ]);
    }

    public function activeAjax(Request $request, Company $company, Brand $brand, Location $location, UserProfile $profile)
    {
        \request()->merge([
            'filters' => [
                [
                    'name'  => 'profileFilter',
                    'value' => $profile,
                ],
                [
                    'name'  => 'locationFilter',
                    'value' => $location,
                ],
            ],
        ]);

        return response()->json(CatalogFacade::dataTableIndex($request, CatalogUserCredits::class));
    }

    public function pastCredits(Request $request, Company $company, Brand $brand, Location $location, UserProfile $profile)
    {
        \request()->merge([
            'filters' => [
                [
                    'name'  => 'profileFilter',
                    'value' => $profile,
                ],
                [
                    'name'  => 'locationFilter',
                    'value' => $location,
                ],
            ],
        ]);

        return response()->json(CatalogFacade::dataTableIndex($request, CatalogUserCreditsPast::class));

    }

    public function usedCredits(Request $request, Company $company, Brand $brand, Location $location, UserProfile $profile)
    {
        \request()->merge([
            'filters' => [
                [
                    'name'  => 'profileFilter',
                    'value' => $profile,
                ],
                [
                    'name'  => 'locationFilter',
                    'value' => $location,
                ],
            ],
        ]);

        return response()->json(CatalogFacade::dataTableIndex($request, CatalogUserCreditsUsed::class));

    }

    public function deleteCredits(Request $request, Company $company, Brand $brand, Location $location, UserProfile $profile)
    {
        \request()->merge([
            'filters' => [
                [
                    'name'  => 'profileFilter',
                    'value' => $profile,
                ],
                [
                    'name'  => 'locationFilter',
                    'value' => $location,
                ],
            ],
        ]);

        return response()->json(CatalogFacade::dataTableIndex($request, CatalogUserCreditsDelete::class));

    }

    public function userPurchases(Request $request, Company $company, Brand $brand, Location $location, UserProfile $profile)
    {

        \request()->merge([
            'filters' => [
                [
                    'name'  => 'profileFilter',
                    'value' => $profile,
                ],
                [
                    'name'  => 'locationFilter',
                    'value' => $location,
                ],
            ],
        ]);

        return response()->json(CatalogFacade::dataTableIndex($request, CatalogUserPurchase::class));

    }


    public function info(Request $request, Company $company, Brand $brand, Location $location, UserProfile $profile)
    {

        if ($profile->countries_id == null) {
            $country = ' ';
        } else {
            $country = $profile->country->name;
        }

        if ($profile->country_states_id == null) {
            $state = ' ';
        } else {
            $state = $profile->state->name;
        }

        if (!$profile->birth_date) {
            $age = '--';
        } else {
            $birth_date = Carbon::parse($profile->birth_date);

            $age = $birth_date->diffInYears(Carbon::now());
        };

        return VistasGafaFit::view('admin.location.users.info', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
            'profile'  => $profile,
            'age'      => $age,
            'country'  => $country,
            'state'    => $state,
        ]);
    }

    public function purchaseInfo(Request $request, Company $company, Brand $brand, Location $location, UserProfile $profile, Purchase $purchase)
    {
        $catalogo = new CatalogUserPurchase();
        $ajaxpurchase = route('admin.company.brand.locations.users.ajax.purchase', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
            'catalogo' => $catalogo,
            'profile'  => $profile,
        ]);

        return VistasGafaFit::view('admin.location.users.purchases', [
            'catalogo'      => $catalogo,
            'ajaxDatatable' => $ajaxpurchase,
            'profile'       => $profile,
            'purchase'      => $purchase,
        ]);


    }

    public function purchaseItems(Request $request, Company $company, Brand $brand, Location $location, Purchase $purchase)
    {

        //DiscountCode todo que devuelva la relacion de decuento
        if ($purchase->hasDiscountCode()) {
            $codesDetails = $purchase->discountCode;
        }

        //Giftcard
        if ($purchase->isGiftCard()) {
            $giftcardDetail = ($purchase->giftcard) ? $purchase->giftcard : null;
            $user = ($giftcardDetail->redempted_by_user_profiles_id) ? $giftcardDetail->user_profile : null;
            $admin = ($giftcardDetail->redempted_by_admin_profiles_id) ? $giftcardDetail->admin_profile : null;
            $redeem_at = date_create($giftcardDetail->redempted_at);
        }
        //Compra normal
        $purchaseDetails = $purchase->items;
        $currency = $purchase->currency;

        return VistasGafaFit::view('admin.location.users.purchasesInfo', [
            'purchase'       => $purchase,
            'currency'       => $currency,
            'details'        => $purchaseDetails,
            'detailGiftCard' => $giftcardDetail ?? null,
            'user'           => $user ?? null,
            'admin'          => $admin ?? null,
            'redeem_at'      => ($purchase->giftcard) ? date_format($redeem_at, 'd/m/Y') : '',
            'codesDetails'   => $codesDetails ?? null,
        ]);
    }


    public function edit(Request $request, Company $company, Brand $brand, Location $location, UserProfile $user)
    {
        if (!$user->toArray()) {
            $data = $request->has('email') && $request->get('email') ? ['email' => $request->get('email')] : [];
            if ($request->has('on_created') && $request->get('on_created') !== '') {
                $data['on_created'] = $request->get('on_created');
            }
            if ($request->has('edit_modal_id') && $request->get('edit_modal_id') !== '') {
                $data['edit_modal_id'] = $request->get('edit_modal_id');
            }

            return VistasGafaFit::view('admin.location.users.email', $data);
        }

        return VistasGafaFit::view('admin.location.users.edit', [
            'user'      => $user,
            'urlForm'   => route('admin.company.brand.locations.users.save', [
                'company'  => $company,
                'brand'    => $brand,
                'location' => $location,
                'user'     => $user,
            ]),
            'countries' => Countries::all(),
        ]);
    }

    /**
     * @param Request     $request
     * @param Company     $company
     * @param Brand       $brand
     * @param Location    $location
     *
     * @param UserProfile $user
     *
     * @return \App\Librerias\Catalog\LibCatalogoModel|UserProfile|\Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save(Request $request, Company $company, Brand $brand, Location $location, UserProfile $user)
    {
        $new = CatalogFacade::save($request, CatalogUserProfile::class);

        return $new;
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveNew(Request $request, Company $company, Brand $brand, Location $location)
    {
        $user = LibUsers::saveLocationUser($request, $company);

        return VistasGafaFit::view('admin.location.users.edit', [
            'user'      => $user,
            'urlForm'   => route('admin.company.brand.locations.users.save', [
                'company'  => $company,
                'brand'    => $brand,
                'location' => $location,
                'user'     => $user,
            ]),
            'countries' => Countries::all(),
        ]);
    }

    /**
     * @param Request     $request
     * @param Company     $company
     * @param Brand       $brand
     * @param Location    $location
     * @param UserProfile $profile
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function activeMemberships(Request $request, Company $company, Brand $brand, Location $location, UserProfile $profile)
    {
        \request()->merge([
            'filters' => [
                [
                    'name'  => 'profileFilter',
                    'value' => $profile,
                ],
                [
                    'name'  => 'locationFilter',
                    'value' => $location,
                ],
            ],
        ]);

        return response()->json(CatalogFacade::dataTableIndex($request, CatalogUserActiveMemberships::class));
    }

    public function expiredMemberships(Request $request, Company $company, Brand $brand, Location $location, UserProfile $profile)
    {
        \request()->merge([
            'filters' => [
                [
                    'name'  => 'profileFilter',
                    'value' => $profile,
                ],
                [
                    'name'  => 'locationFilter',
                    'value' => $location,
                ],
            ],
        ]);

        return response()->json(CatalogFacade::dataTableIndex($request, CatalogUserExpiredMemberships::class));
    }

    /**
     * @param Request     $request
     * @param Company     $company
     * @param Brand       $brand
     * @param Location    $location
     * @param UserProfile $profile
     */
    public function saveCredit(Request $request, Company $company, Brand $brand, Location $location, UserProfile $profile)
    {
        LibUserCredits::modifyCredits($request, $profile, $location, $company, $brand);
    }

    /**
     * @param Request     $request
     * @param Company     $company
     * @param Brand       $brand
     * @param Location    $location
     * @param UserProfile $profile
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveMembership(Request $request, Company $company, Brand $brand, Location $location, UserProfile $profile)
    {
        LibUserCredits::modifyActiveMembership($request, $profile, $location, $company, $brand);

        return response()->json(true);
    }

    /**
     * @param Request     $request
     * @param Company     $company
     * @param Brand       $brand
     * @param Location    $location
     * @param UserProfile $profile
     */
    public function deleteCredit(Request $request, Company $company, Brand $brand, Location $location, UserProfile $profile)
    {
        LibUserCredits::cancelCredits($request, $company, $brand, $location, $profile);
    }

    /**
     * @param Request          $request
     * @param Company          $company
     * @param Brand            $brand
     * @param Location         $location
     * @param UserProfile      $profile
     * @param UsersMemberships $membership
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function deleteMembership(Request $request, Company $company, Brand $brand, Location $location, UserProfile $profile, UsersMemberships $membership)
    {
        return response()->json($membership->cancelMembership());
//        LibGafaPay::cancelSubscription();
//        CatalogFacade::delete($request, CatalogUserActiveMemberships::class);
    }

    /**
     * @param Request     $request
     * @param Company     $company
     * @param Brand       $brand
     * @param Location    $location
     * @param UserProfile $profile
     * @param Purchase    $purchase
     */
    public function cancelSubscription(Request $request, Company $company, Brand $brand, Location $location, UserProfile $profile, Purchase $purchase)
    {
        $payment = $purchase->active_subscription_payment()->first();
        if ($payment) {
            $subscription = $payment->subscription;
            $subscription->cancel();
        }
    }

    /**
     * @param Request     $request
     * @param Company     $company
     * @param Brand       $brand
     * @param Location    $location
     * @param UserProfile $profile
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function unblockConfirm(Request $request, Company $company, Brand $brand, Location $location, UserProfile $profile)
    {
        // $profile->blocked_reserve = false;

        //dd($profile->blocked_reserve);
        return VistasGafaFit::view('admin.location.users.unblockUser', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
            'profile'  => $profile,
            'urlForm'  => route('admin.company.brand.locations.users.unblock.user', [
                'company'  => $company,
                'brand'    => $brand,
                'location' => $location,
                'profile'  => $profile,
            ]),
        ]);
    }

    /**
     * @param Request     $request
     * @param Company     $company
     * @param Brand       $brand
     * @param Location    $location
     * @param UserProfile $profile
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function unblockUser(Request $request, Company $company, Brand $brand, Location $location, UserProfile $profile)
    {
        $profile->unblock();
        $profile->save();

        return redirect()->back();


    }
}
