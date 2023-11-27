<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 16/05/2018
 * Time: 05:33 PM
 */

namespace App\Http\Controllers\Admin\Location\Reservations;

use App\Admin;
use App\Http\Controllers\Admin\Location\LocationLevelController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Location\CatalogWaitlist;
use App\Librerias\Catalog\Tables\Location\Reservations\CatalogReservations;
use App\Librerias\Catalog\Tables\Location\Reservations\CatalogUsersReservations;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Reservation\LibReservation;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Http\Requests\AdminRequest as Request;
use App\Models\Location\Location;
use App\Models\User\UserProfile;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class UsersReservationsController extends LocationLevelController
{

    /**
     * UsersReservationsController constructor.
     *
     * @param Admin $admin
     */
    function __construct(Admin $admin)
    {
        parent::__construct($admin);
        $Brands = $this->getBrand();
        $location = $this->getLocation();

        $this->middleware(function ($request, $next) use ($location) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::RESERVATION_VIEW, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
        $this->middleware(function ($request, $next) use ($location) {
            if (\request()->ajax()) {
                $filters = new Collection((array)$request->get('filters', []));
                if (!$filters)
                    return abort(404);

                $locationId = (int)$filters->filter(function ($item) {
                        return $item['name'] === 'locations_id';
                    })->first()['value'] ?? 0;

                if ($locationId !== $this->getLocation()->id)
                    return abort(404);
            }

            return $next($request);
        })->only([
            'Index',
        ]);

        $this->middleware(function ($request, $next) use ($location) {


            if (\request()->method() === 'POST') {
                if (!\request()->has('locations_id') || \request()->get('locations_id') != \request()->route('location')->id)
                    return abort(404);
            }


            $user = \auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::RESERVATION_VIEW, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'seeReservations',
            'reservationUrl',
        ]);

    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function Index(Request $request, Company $company, Brand $brand)
    {

        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogUsersReservations::class));

        }
        $catalogo = new CatalogUsersReservations();

        return VistasGafaFit::view('admin.location.reservations.users.index', [
            'catalogo' => $catalogo,
        ]);
    }

    /**
     * todo: validar location.
     *
     * @param Request     $request
     * @param Company     $company
     * @param Brand       $brand
     * @param Location    $location
     * @param UserProfile $profile
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function seeReservations(Request $request, Company $company, Brand $brand, Location $location, UserProfile $profile)
    {
        $birth_date = Carbon::parse($profile->birth_date);
        $fecha = $birth_date->diffInYears(Carbon::now());

        $catalogo  = new CatalogReservations();
        $catalogo1 = new CatalogWaitlist();

        $reservations = LibReservation::userFuturesReservations($profile, $brand);

        $routeDataTable = route('admin.company.brand.locations.reservations.users.reservations.url', [
            'brand'    => $this->getBrand(),
            'company'  => $this->getCompany(),
            'location' => $this->getLocation(),
            'profile'  => $profile,
            'catalogo' => $catalogo,
        ]);

        $routeDataTable1 = route(
            'admin.company.brand.locations.reservations.users.waitlist.url',
            [
                'brand'     => $this->getBrand(),
                'company'   => $this->getCompany(),
                'location'  => $this->getLocation(),
                'profile'   => $profile,
                'catalogo1' => $catalogo1,
            ]
        );

        return VistasGafaFit::view('admin.location.reservations.users.list', [
            'profile'        => $profile,
            'fecha'          => $fecha,
            'catalogo'       => $catalogo,
            'catalogo1'      => $catalogo1,
            'reservations'   => $reservations,
            'ajaxDatatable'  => $routeDataTable,
            'ajaxDatatable1' => $routeDataTable1,
        ]);
    }

    /**
     * @param Request     $request
     * @param Company     $company
     * @param Brand       $brand
     * @param Location    $location
     *
     * @param UserProfile $profile
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reservationUrl(Request $request, Company $company, Brand $brand, Location $location, UserProfile $profile)
    {
        \request()->merge([
            'filters' => [
                [
                    'name'  => 'profile',
                    'value' => $profile,
                ],
                [
                    'name'  => 'locations_id',
                    'value' => $location->id,
                ],
            ],
        ]);

        return response()->json(CatalogFacade::dataTableIndex($request, CatalogReservations::class));
    }

    /**
     * @param Request     $request
     * @param Company     $company
     * @param Brand       $brand
     * @param Location    $location
     *
     * @param UserProfile $profile
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function waitlistUrl(Request $request, Company $company, Brand $brand, Location $location, UserProfile $profile)
    {
        \request()->merge(
            [
                'filters' => [
                    [
                        'name'  => 'profile',
                        'value' => $profile,
                    ],
                    [
                        'name'  => 'locations_id',
                        'value' => $location->id,
                    ],
                ],
            ]
        );

        return response()->json(CatalogFacade::dataTableIndex($request, CatalogWaitlist::class));
    }
}
