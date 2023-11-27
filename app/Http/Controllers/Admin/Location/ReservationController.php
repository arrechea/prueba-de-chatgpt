<?php

namespace App\Http\Controllers\Admin\Location;

use App\Admin;
use App\Http\Requests\AdminRequest as Request;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Reservation\LibHandlePurchase;
use App\Librerias\Reservation\LibReservationForm;
use App\Librerias\Reservation\ReservationControllerTrait;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\Maps\MapsObject;
use App\Models\Meeting\Meeting;
use App\Models\Reservation\Reservation;
use App\Models\Room\Room;
use App\Models\User\UserProfile;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class  ReservationController extends LocationLevelController implements ReservationControllerTrait
{
    /**
     * ReservationController constructor.
     *
     * @param Admin $admin
     */
    function __construct(Admin $admin)
    {
        parent::__construct($admin);
        $location = $this->getLocation();
        $this->middleware(function ($request, $next) use ($location) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::RESERVATION_VIEW, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
        $this->middleware(function ($request, $next) use ($location) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::RESERVATION_CREATE, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->except([
            'index',
            'reservate',
            'getForm',
        ]);

        $this->middleware(function ($request, $next) use ($location) {

            $reservation = \request()->route('meeting');
            if (!$reservation || $reservation->locations_id != \request()->route('location')->id) {
                return abort(404);
            }
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
            'seeMeeting',
        ]);

        $this->middleware(function ($request, $next) use ($location) {

            $reservation = \request()->route('reservation');

            if (!$reservation || $reservation->locations_id != $location->id) {
                return abort(404);
            }

            $user = \auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::RESERVATION_DELETE, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'delete',
            'deletePost',
        ]);

        $this->middleware(function ($request, $next) use ($location) {
            $user = \auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::ATTENDANCE_LIST_EDIT, $location)) {
                throw new NotFoundHttpException();
            }

            if (!$request->has('assist')) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'attendanceList',
        ]);

        $this->middleware(function ($request, $next) use ($location) {
            $user = \auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::RESERVATION_EDIT, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'positionChange',
        ]);
    }

    /**
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(Company $company, Brand $brand, Location $location)
    {
        return redirect()->route('admin.company.brand.locations.reservations.users.index', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request, Company $company, Brand $brand, Location $location, Room $room)
    {
        return VistasGafaFit::view('admin.location.reservations.create.index', [
            'room'      => $room,
            'locations' => $brand->locations,
        ]);
    }


    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function getForm(\Illuminate\Http\Request $request)
    {
        return LibReservationForm::generateForm($request, $this->getCompany(), $this->getLocation(), true);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function getFormTemplate(\Illuminate\Http\Request $request)
    {
        return LibReservationForm::generateForm($request, $this->getCompany(), $this->getLocation(), true);
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function reservate(\Illuminate\Http\Request $request)
    {
        return LibHandlePurchase::purchase($request, $this->getCompany(), $this->getLocation(), true);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param Company                  $company
     * @param Brand                    $brand
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateGiftCode(\Illuminate\Http\Request $request, Company $company, Brand $brand)
    {
        return LibReservationForm::generateGiftCode($company, $brand);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param Company                  $company
     * @param Brand                    $brand
     * @param Location                 $location
     * @param string                   $code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkGiftCode(\Illuminate\Http\Request $request, Company $company, Brand $brand, Location $location, string $code)
    {
        $isValid = LibReservationForm::isGiftCodeValidToGenerate($code, $brand);
        if ($isValid) {
            return response()->json(true, 200);
        } else {
            return response()->json(true, 409);
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param Company                  $company
     * @param Brand                    $brand
     * @param Location                 $location
     * @param string                   $code
     *
     * @param UserProfile              $userProfile
     *
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function checkDiscountCode(\Illuminate\Http\Request $request, Company $company, Brand $brand, Location $location, string $code, UserProfile $userProfile)
    {
        return LibReservationForm::responseDiscountCodeValid($code, $brand, $userProfile, $request);
    }

    /**
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Meeting  $meeting
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function seeMeeting(Company $company, Brand $brand, Location $location, Meeting $meeting)
    {
        $start = $meeting->start_date;

        $meeting->load([
            'staff',
            'service',
        ]);
        $reservations = $meeting
            ->reservation()
            ->orderBy('reservations.meeting_position', 'asc')
            ->with([
                'user',
            ])
            ->get();

        $staff = $meeting->staff;
        $services = $meeting->service;

        return VistasGafaFit::view('admin.location.reservations.staff.tabs', [
            'meeting'      => $meeting,
            'staff'        => $staff,
            'services'     => $services,
            'title'        => $start->formatLocalized('%A %d %B'),
            'start_date'   => $meeting->start_date,
            'reservations' => $reservations,
        ]);
    }

    /**
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Meeting  $meeting
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function seeMeetingPrint(Company $company, Brand $brand, Location $location, Meeting $meeting)
    {
        $start = $meeting->start_date;

        $reservations = $meeting
            ->reservation()
            ->orderBy('reservations.meeting_position', 'asc')
            ->with([
                'user',
            ])
            ->get();

        $staff = $meeting->staff;
        $services = $meeting->service;

        return VistasGafaFit::view('admin.location.reservations.listPrint', [
            'meeting'      => $meeting,
            'staff'        => $staff,
            'services'     => $services,
            'title'        => $start->formatLocalized('%A %d %B'),
            'start_date'   => $meeting->start_date,
            'reservations' => $reservations,
        ]);
    }

    /**
     * Eliminacion de una reserva, que va a devolver los creditos a usuario
     *
     * @param Request     $request
     * @param Company     $company
     * @param Brand       $brand
     * @param Location    $location
     * @param Meeting     $meeting
     *
     * @param Reservation $reservation
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete(Request $request, Company $company, Brand $brand, Location $location, Meeting $meeting, Reservation $reservation)
    {
        return VistasGafaFit::view('admin.location.reservations.delete', [
            'company'     => $this->getCompany(),
            'brand'       => $this->getBrand(),
            'location'    => $this->getLocation(),
            'meeting'     => $meeting,
            'reservation' => $reservation->id,
        ]);
    }

    /**
     * Funcion para cancelar una reserva
     *
     * @param Request     $request
     * @param Company     $company
     * @param Brand       $brand
     * @param Location    $location
     * @param Meeting     $meeting
     * @param Reservation $reservation
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deletePost(Request $request, Company $company, Brand $brand, Location $location, Meeting $meeting, Reservation $reservation)
    {
        if ($reservation->canBeCancelled()) {
            $reservation->cancel();

            return response()->json($reservation);
        }

        return abort(403);
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Meeting  $meeting
     */
    public function attendanceList(Request $request, Company $company, Brand $brand, Location $location, Meeting $meeting)
    {
        $list = $request->input('assist');
        $reservations = $meeting->reservation()->where('cancelled', 0)->get();
        if (is_array($list)) {
            foreach ($list as $k => $v) {
                $reservation = $reservations->first(function ($item) use ($k) {
                    return $item->id === $k;
                });
                if ($reservation) {
                    if ($v === 'lock') {
                        $reservation->attendance = '';
                        $reservation->user->blocked_reserve = true;
//                        echo ($reservation->user->blocked_reserve);
                        $reservation->user->save();
                    } else {
                        echo($v);
                        $reservation->attendance = $v;
                        $reservation->save();

                    }
                }
            }
        }
    }

    /**
     * @param Request     $request
     * @param Company     $company
     * @param Brand       $brand
     * @param Location    $location
     * @param Reservation $reservation
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\Response
     */
    public function positionChange(Request $request, Company $company, Brand $brand, Location $location, Reservation $reservation)
    {
        if ($request->filled('position') && !!$reservation->maps_id) {
            $position = MapsObject::where([
                ['maps_id', $reservation->maps_id],
                ['position_number', $request->input('position')],
            ])->first();
            if (!$position) {
                throw new NotFoundHttpException(__('reservations.positionNotFoundError'));
            } else {
                $reservation->meeting_position = $position->position_number;
                $reservation->maps_objects_id = $position->id;
                $reservation->save();
            }
        }

        return response('true');
    }

    public function printNewReservation(Request $request, Company $company, Brand $brand, Location $location, Reservation $reservation)
    {
        return VistasGafaFit::view('admin.location.reservations.listEntry', [
            'reservation' => $reservation,
        ]);
    }
}
