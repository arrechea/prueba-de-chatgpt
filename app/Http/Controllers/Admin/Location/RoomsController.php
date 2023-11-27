<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 03/05/2018
 * Time: 05:54 PM
 */

namespace App\Http\Controllers\Admin\Location;

use App\Admin;
use App\Http\Requests\AdminRequest as Request;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Location\CatalogMeeting;
use App\Librerias\Catalog\Tables\Location\CatalogRoom;
use App\Librerias\Catalog\Tables\Location\Rooms\CatalogRoomMeetingNoReservation;
use App\Librerias\Catalog\Tables\Location\Rooms\CatalogRoomMeetings;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\Meeting\Meeting;
use App\Models\Room\Room;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RoomsController extends LocationLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $location = $this->getLocation();


        $this->middleware(function ($request, $next) use ($location) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::ROOMS_VIEW, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
        $this->middleware(function ($request, $next) use ($location) {
            if (\request()->ajax()) {
                $filters = new Collection((array)$request->get('filters', []));
                if (!$filters)
                    return abort(404);

                $locations_id = (int)$filters->filter(function ($item) {
                        return $item['name'] === 'locations_id';
                    })->first()['value'] ?? 0;

                if ($locations_id !== \request()->route('location')->id)
                    return abort(404);
            }

            return $next($request);
        })->only([
            'index',
        ]);
        $this->middleware(function ($request, $next) use ($location) {
            $room = \request()->route('room');
            if (!$room || $room->locations_id != \request()->route('location')->id) {
                return abort(404);
            }

            if (\request()->method() === 'POST') {
                if (!\request()->has('locations_id') || \request()->get('locations_id') != \request()->route('location')->id ||
                    !\request()->has('brands_id') || \request()->get('brands_id') != \request()->route('brand')->id ||
                    !\request()->has('companies_id') || \request()->get('companies_id') != \request()->route('company')->id
                )
                    return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::ROOMS_EDIT, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'edit',
            'saveEdit',
        ]);
        $this->middleware(function ($request, $next) use ($location) {
            if (\request()->method() === 'POST') {
                if (!\request()->has('locations_id') || \request()->get('locations_id') != \request()->route('location')->id ||
                    !\request()->has('brands_id') || \request()->get('brands_id') != \request()->route('brand')->id ||
                    !\request()->has('companies_id') || \request()->get('companies_id') != \request()->route('company')->id
                )
                    return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::ROOMS_CREATE, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'create',
            'saveNew',
        ]);
        $this->middleware(function ($request, $next) use ($location) {
            $room = \request()->route('room');
            if (!$room || $room->locations_id != \request()->route('location')->id) {
                return abort(404);
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::ROOMS_DELETE, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'delete',
            'deletePost',
        ]);
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request, Company $company, Brand $brand, Location $location)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogRoom::class));
        }

        $catalogo = new CatalogRoom();

        return VistasGafaFit::view('admin.location.rooms.index', [
            'catalogo' => $catalogo,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        $urlForm = route('admin.company.brand.locations.rooms.save.new', [
            'company'  => $this->getCompany(),
            'brand'    => $this->getBrand(),
            'location' => $this->getLocation(),
        ]);

        return VistasGafaFit::view('admin.location.rooms.create.index', [
            'urlForm'  => $urlForm,
            'langFile' => new Collection(Lang::get('rooms')),
        ]);
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Room     $room
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, Company $company, Brand $brand, Location $location, Room $room)
    {
        $micro = \App\Librerias\Catalog\LibDatatable::GetTableId();
        //$meeting = $room->meetings;
//
//        if ($request->ajax()) {
//            return response()->json(CatalogFacade::dataTableIndex($request, CatalogRoomMeetings::class));
//        }

        $catalogo = new CatalogRoomMeetings();
        $catalogo2 = new CatalogRoomMeetingNoReservation();

        $ajaxDatatable = route('admin.company.brand.locations.rooms.ajax.meetings', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
            'catalogo' => $catalogo,
            'room'     => $room,
        ]);

        $ajaxDatatable2 = route('admin.company.brand.locations.rooms.ajax', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
            'catalogo' => $catalogo2,
            'room'     => $room,
        ]);

        $urlForm = route('admin.company.brand.locations.rooms.save', [
            'company'  => $this->getCompany(),
            'brand'    => $this->getBrand(),
            'location' => $this->getLocation(),
            'room'     => $room,
        ]);

        return VistasGafaFit::view('admin.location.rooms.edit.index', [
            'room'           => $room,
            'urlForm'        => $urlForm,
            'catalogo'       => $catalogo,
            'catalogo2'      => $catalogo2,
            'ajaxDatatable'  => $ajaxDatatable,
            'ajaxDatatable2' => $ajaxDatatable2,
            'langFile'       => new Collection(Lang::get('rooms')),
        ]);
    }

    public function meetingsAjax(Request $request, Company $company, Brand $brand, Location $location, Room $room)
    {
        \request()->merge([
            'filters' => [
                [
                    'name'  => 'roomFilter',
                    'value' => $room,
                ],
                [
                    'name'  => 'locationFilter',
                    'value' => $location,
                ],
            ],
        ]);

        return response()->json(CatalogFacade::dataTableIndex(\request(), CatalogRoomMeetingNoReservation::class));

    }


    public function reservationsAjax(Request $request, Company $company, Brand $brand, Location $location, Room $room)
    {

        \request()->merge([
            'filters' => [
                [
                    'name'  => 'roomFilter',
                    'value' => $room,
                ],
                [
                    'name'  => 'locationFilter',
                    'value' => $location,
                ],
            ],
        ]);

        return response()->json(CatalogFacade::dataTableIndex(\request(), CatalogRoomMeetings::class));

    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Room     $room
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function delete(Request $request, Company $company, Brand $brand, Location $location, Room $room)
    {
        return VistasGafaFit::view('admin.location.rooms.edit.delete', [
            'room' => $room,
        ]);
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveNew(Request $request, Company $company, Brand $brand, Location $location)
    {
        $new = CatalogFacade::save($request, CatalogRoom::class);

        return redirect()->route('admin.company.brand.locations.rooms.edit', [
            'company'  => $this->getCompany(),
            'brand'    => $this->getBrand(),
            'location' => $this->getLocation(),
            'room'     => $new,
        ]);
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Room     $room
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveEdit(Request $request, Company $company, Brand $brand, Location $location, Room $room)
    {
        CatalogFacade::save($request, CatalogRoom::class);

        return redirect()->back();
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Room     $room
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function deletePost(Request $request, Company $company, Brand $brand, Location $location, Room $room)
    {
        CatalogFacade::delete($request, CatalogRoom::class);

        return redirect()->route('admin.company.brand.locations.rooms.index', [
            'company'  => $this->getCompany(),
            'brand'    => $this->getBrand(),
            'location' => $this->getLocation(),
            'room'     => $room,
        ]);
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Room     $room
     * @param Meeting  $meeting
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     *
     */
    public function individualMeeting(Request $request, Company $company, Brand $brand, Location $location, Room $room, Meeting $meeting)
    {
        $saveMeetingRoom = route('admin.company.brand.locations.rooms.meetings.save.refresh', [
            'company'  => $this->getCompany(),
            'brand'    => $this->getBrand(),
            'location' => $this->getLocation(),
            'meeting'  => $meeting,
            'room'     => $room,
        ]);

        //dd($urlForm);
        return VistasGafaFit::view('admin.location.rooms.buttons.refreshForm', [
            'saveMeetingRoom' => $saveMeetingRoom,
        ]);
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Room     $room
     * @param Meeting  $meeting
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshIndividualMeeting(Request $request, Company $company, Brand $brand, Location $location, Room $room, Meeting $meeting)
    {
        $meeting->capacity = $room->capacity;
        $meeting->details = $room->details;
        $meeting->maps_id = $room->maps_id;
        $meeting->save();

        return response()->json($meeting);
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Room     $room
     * @param Meeting  $meeting
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function deleteMeeting(Request $request, Company $company, Brand $brand, Location $location, Room $room, Meeting $meeting)
    {
        $deleteMeetingRoom = route('admin.company.brand.locations.rooms.meetings.delete.individual', [
            'company'  => $this->getCompany(),
            'brand'    => $this->getBrand(),
            'location' => $this->getLocation(),
            'meeting'  => $meeting,
            'room'     => $room,
        ]);

        //dd($urlForm);
        return VistasGafaFit::view('admin.location.rooms.buttons.deleteForm', [
            'deleteMeetingRoom' => $deleteMeetingRoom,
        ]);
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Room     $room
     * @param Meeting  $meeting
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteIndividualMeeting(Request $request, Company $company, Brand $brand, Location $location, Room $room, Meeting $meeting)
    {
        $now = $brand->now();
        $meeting->deleted_at = $now;
        $meeting->save();

        return response()->json($meeting);
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Room     $room
     * @param Meeting  $meeting
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function deleteMeetingWithReservations(Request $request, Company $company, Brand $brand, Location $location, Room $room, Meeting $meeting)
    {
        $deleteMeetingReservations = route('admin.company.brand.locations.rooms.meetings.delete.mreservations', [
            'company'  => $this->getCompany(),
            'brand'    => $this->getBrand(),
            'location' => $this->getLocation(),
            'meeting'  => $meeting,
            'room'     => $room,
        ]);

        //dd($urlForm);
        return VistasGafaFit::view('admin.location.rooms.buttons.cancelReservationsForm', [
            'deleteMeetingReservations' => $deleteMeetingReservations,
        ]);
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Room     $room
     * @param Meeting  $meeting
     *
     * @return mixed|null
     */
    public function deleteIndividualMeetingWithReservations(Request $request, Company $company, Brand $brand, Location $location, Room $room, Meeting $meeting)
    {

        if (!$meeting->isPast()) {
            $now = $brand->now();

            $meeting->cancel(true);
            $id = $meeting->id;
            $meeting->deleted_at = $now;
            $meeting->save();

            return $id;
        }

        return null;

    }

    public function updateMeetingsWithoutReservations(Request $request, Company $company, Brand $brand, Location $location, Room $room, Meeting $meeting)
    {
        $urlUpdateMeetings = route('admin.company.brand.locations.rooms.meetings.update.meetings', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
            'room'     => $room,
        ]);

        return VistasGafaFit::view('admin.location.rooms.buttons.updateMeetings', [
            'room'              => $room,
            'urlUpdateMeetings' => $urlUpdateMeetings,
        ]);
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Room     $room
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateMeetingsWithoutReservationsDiferentConfigurations(Request $request, Company $company, Brand $brand, Location $location, Room $room)
    {
        \request()->merge([
            'filters' => [
                [
                    'name'  => 'roomFilter',
                    'value' => $room,
                ],
                [
                    'name'  => 'locationFilter',
                    'value' => $location,
                ],
            ],
        ]);
        $query = CatalogRoomMeetingNoReservation::whereNotNull('id');
        CatalogRoomMeetingNoReservation::filtrarQueries($query);
        //UPDATE
        $updated = $query->update([
            'capacity' => $room->capacity,
            'details'  => $room->details,
            'maps_id'  => $room->maps_id,
        ]);
        if ($updated) {
            return response()->json('updated', 202);
        } else {
            return response()->json('false', 409);
        }
    }

    public function cancelMeetingsWithoutReservations(Request $request, Company $company, Brand $brand, Location $location, Room $room)
    {
        $urlCancelMeetings = route('admin.company.brand.locations.rooms.meetings.cancel.meetings', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
            'room'     => $room,
        ]);

        return VistasGafaFit::view('admin.location.rooms.buttons.cancelMeetingsForm', [
            'urlCancelMeetings' => $urlCancelMeetings,
        ]);

    }

    public function cancelMeetingsWithoutReservationsDifferentConfigurations(Request $request, Company $company, Brand $brand, Location $location, Room $room, Meeting $meeting)
    {

        \request()->merge([
            'filters' => [
                [
                    'name'  => 'roomFilter',
                    'value' => $room,
                ],
                [
                    'name'  => 'locationFilter',
                    'value' => $location,
                ],
            ],
        ]);
        $query = CatalogRoomMeetingNoReservation::whereNotNull('id');
        CatalogRoomMeetingNoReservation::filtrarQueries($query);

        //CANCEL
        foreach ($query->get() as $meeting) {
            $meeting->cancel(true);
            $meeting->deleted_at = $brand->now();
            $meeting->save();
        }

    }

    public function cancelMeetingsWithReservations(Request $request, Company $company, Brand $brand, Location $location, Room $room)
    {
        $urlCancelMeetingswithreservations = route('admin.company.brand.locations.rooms.meetings.cancel.reservations', [
            'company'  => $company,
            'brand'    => $brand,
            'location' => $location,
            'room'     => $room,
        ]);

        return VistasGafaFit::view('admin.location.rooms.buttons.cancelMeetingsReservationsForm', [
            'urlCancelMeetingswithreservations' => $urlCancelMeetingswithreservations,
        ]);

    }

    public function cancelMeetingsWithReservationsDifferentConfigurations(Request $request, Company $company, Brand $brand, Location $location, Room $room, Meeting $meeting)
    {


        \request()->merge([
            'filters' => [
                [
                    'name'  => 'roomFilter',
                    'value' => $room,
                ],
                [
                    'name'  => 'locationFilter',
                    'value' => $location,
                ],
            ],
        ]);
        $query = CatalogRoomMeetings::whereNotNull('id');
        CatalogRoomMeetings::filtrarQueries($query);
        // dd($query->get()->toArray());
        //CANCEL
        foreach ($query->get() as $meeting) {
            $meeting->cancel(true);
            $meeting->deleted_at = $brand->now();
            $meeting->save();
        }

    }
}
