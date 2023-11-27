<?php

namespace App\Http\Controllers\Admin\Location;

use App\Admin;
use App\Http\Controllers\AdminController;
use App\Librerias\Calendars\LibCalendar;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Location\CatalogMeeting;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\Meeting\Meeting;
use App\Models\Room\Room;
use App\User;
use function MongoDB\BSON\toJSON;
use App\Http\Requests\AdminRequest as Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CalendarController extends LocationLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $location = $this->getLocation();

        $this->middleware(function ($request, $next) use ($location) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::CALENDAR_VIEW, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
    }

    public function index(Request $request, Company $company, Brand $brand, Location $location, Room $room)
    {
        $rooms = $location->activeRooms;
        if ($room->attributesToArray()) {
            if (!$room->isActive()) {
                abort(404);
            }

            $room = $room->id;
        } else {
            $room = $rooms->first()->id ?? null;
        }

        return VistasGafaFit::view('admin.location.calendar.index', [
            'rooms_id' => $room,
            'rooms'    => $rooms,
        ]);
    }


}
