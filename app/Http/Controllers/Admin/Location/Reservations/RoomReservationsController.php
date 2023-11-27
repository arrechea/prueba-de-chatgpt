<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 16/05/2018
 * Time: 05:14 PM
 */

namespace App\Http\Controllers\Admin\Location\Reservations;


use App\Admin;
use App\Http\Controllers\Admin\Location\LocationLevelController;
use App\Librerias\Calendars\LibCalendar;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Location\Reservations\CatalogRoomReservations;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Http\Requests\AdminRequest as Request;
use App\Models\Location\Location;
use App\Models\Room\Room;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class RoomReservationsController extends LocationLevelController
{

    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $Brands = $this->getBrand();
        $Locations = $this->getLocation();

        $this->middleware(function ($request, $next) use ($Locations) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::RESERVATION_VIEW, $Locations)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
        $this->middleware(function ($request, $next) use ($Locations) {
            if (\request()->ajax()) {
                $filters = new Collection((array)$request->get('filters', []));
                if (!$filters)
                    return abort(404);

                $location_id = (int)$filters->filter(function ($item) {
                        return $item['name'] === 'locations_id';
                    })->first()['value'] ?? 0;

                if ($location_id !== \request()->route('location')->id)
                    return abort(404);
            }

            return $next($request);
        })->only([
            'Index'
        ]);


    }

    /**
     * Mostrar las reservaciones en el calendario
     *
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Room     $room
     *
     * @return array
     */
    public function meetings(Request $request, Company $company, Brand $brand, Location $location, Room $room)
    {
        $events = LibCalendar::getMeetings($request, $room->id, $request->get('start'), $request->get('end'));

        return $events;
    }


    public function Index(Request $request, Company $company, Brand $brand, Location $location)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogRoomReservations::class));

        }
        $catalogo = new CatalogRoomReservations();

        return VistasGafaFit::view('admin.location.reservations.room.index',[
            'catalogo' => $catalogo,
        ]) ;

    }

    /**
     * mostrar calendario en reservas
     *
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Room     $rooms
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function calendar(Request $request, Company $company, Brand $brand, Location $location, Room $room)
    {

        return VistasGafaFit::view('admin.location.reservations.room.calendar',[
            'room' => $room,
        ]);

    }


}
