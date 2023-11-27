<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 16/05/2018
 * Time: 01:24 PM
 */

namespace App\Http\Controllers\Admin\Location;


use App\Admin;
use App\Http\Requests\AdminRequest as Request;
use App\Librerias\Calendars\LibCalendar;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Location\CatalogMeeting;
use App\Librerias\Meetings\LibMeeting;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Servicies\LibServices;
use App\Librerias\Staff\LibStaff;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\Meeting\Meeting;
use App\Models\Room\Room;
use App\Models\Service;
use App\Models\Staff\Staff;
use Carbon\Carbon;
use DateTime;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class MeetingController extends LocationLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $location = $this->getLocation();

        $this->middleware(function ($request, $next) use ($location) {
            $room = \request()->route('room');
            if (!$room || $room->locations_id !== $location->id || !$room->isActive())
                abort(404);

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MEETINGS_VIEW, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
        $this->middleware(function ($request, $next) use ($location) {
            $room = \request()->route('room');
            $meeting = \request()->route('meeting');

            if (\request()->method() === 'POST') {
                if (!\request()->has('locations_id') || \request()->get('locations_id') != \request()->route('location')->id ||
                    !\request()->has('brands_id') || \request()->get('brands_id') != \request()->route('brand')->id ||
                    !\request()->has('companies_id') || \request()->get('companies_id') != \request()->route('company')->id ||
                    !\request()->has('rooms_id') || \request()->get('rooms_id') != \request()->route('room')->id
                )
                    return abort(404);

                $now = $meeting->now();
                $start = $meeting->start_date;

                if ($start->lt($now)) {
                    if ($meeting->start_date != \request()->get('start_date') || $meeting->end_date != \request()->get('end_date')) {
                        return abort(400);
                    }
//                    if ($meeting->staff_id != \request()->get('staff_id')) {
//                        abort(400);
//                    }
                } else {
                    $start_date = (new Carbon(\request()->get('start_date'), $meeting->getTimezone()));
                    $end_date = (new Carbon(\request()->get('end_date'), $meeting->getTimezone()));
                    if ($start_date->lt($now) || $end_date->lt($now)) {
                        abort(400, __('calendar.init-error'));
                    }
                }


            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MEETINGS_EDIT, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'save',
            'edit',
        ]);
        $this->middleware(function ($request, $next) use ($location) {
            if (\request()->method() === 'POST') {
                if (!\request()->has('locations_id') || \request()->get('locations_id') != \request()->route('location')->id ||
                    !\request()->has('brands_id') || \request()->get('brands_id') != \request()->route('brand')->id ||
                    !\request()->has('companies_id') || \request()->get('companies_id') != \request()->route('company')->id ||
                    !\request()->has('rooms_id') || \request()->get('rooms_id') != \request()->route('room')->id
                )
                    return abort(404);

                $room = \request()->route('room');
                $meetingTimeZone = $room->getTimezone();

                $start_date = (new Carbon(\request()->get('start_date'), $meetingTimeZone));
                $end_date = (new Carbon(\request()->get('end_date'), $meetingTimeZone));
                $now = $room->now();

                if ($start_date->lt($now) || $end_date->lt($now)) {
                    abort(400, __('calendar.init-error'));
                }
            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MEETINGS_CREATE, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->
        only([
            'create',
            'saveNew',
        ]);
        $this->middleware(function ($request, $next) use ($location) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MEETINGS_CREATE, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'repeatWeek',
        ]);
        $this->middleware(function ($request, $next) use ($location) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MEETINGS_DELETE, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'delete',
        ]);
    }

    /**
     * Obtiene los meetings dentro del periodo dado
     *
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Room     $room
     *
     * @return array
     */
    public function events(Request $request, Company $company, Brand $brand, Location $location, Room $room)
    {
        $events = LibCalendar::getMeetings($request, $room->id, $request->get('start'), $request->get('end'));

        return $events;
    }

    /**
     * Vista de creación para un meeting del calendario
     *
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Room     $room
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request, Company $company, Brand $brand, Location $location, Room $room)
    {
        if (!$request->has('start') || !$request->has('end'))
            abort(400);

        $timezone = $brand->getTimezone();
        $now = $brand->now();

        $parseStart = (new Carbon($request->get('start')))->toDateTimeString();
        $parseEnd = (new Carbon($request->get('end')))->toDateTimeString();

        $start = (new Carbon($parseStart, $timezone));
        $end = (new Carbon($parseEnd, $timezone));
        $passed = $start->lte($now);

        $day = $start->formatLocalized('%A');
        $mes = $start->formatLocalized('%B');
        $title = __('week-days.' . $day) . ' ' . $start->formatLocalized('%d') . ' ' . __('months.' . $mes);
        $services = LibServices::getParentServices($brand);

        return VistasGafaFit::view('admin.location.calendar.form', [
            'companies_id'   => $company->id,
            'brands_id'      => $brand->id,
            'brandModel'     => $brand,
            'locations_id'   => $location->id,
            'rooms_id'       => $room->id,
            'date'           => $start->format('Y-m-d'),
            'start_time'     => $start->format('h:ia'),
            'end_time'       => $end->format('h:ia'),
            'start_date'     => $start,
            'end_date'       => $end,
            'title'          => $title,
            'services'       => $services,
            'child_services' => $services->first()->childServices ?? [],
            'passed'         => $passed,
            'staff'          => Staff::whereHas('brands', function ($q) use ($brand) {
                $q->where('brands.id', $brand->id);
            })->where('status', 'active')->get(),
        ]);
    }

    /**
     * Vista de edición para un meeting
     *
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Room     $room
     * @param Meeting  $meeting
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, Company $company, Brand $brand, Location $location, Room $room, Meeting $meeting)
    {
        $start = $meeting->start_date;
        $end = $meeting->end_date;
        $now = $brand->now();
        $passed = $start->lte($now);
        $child_services = $meeting->service->parent_id ? $meeting->service->parentService->childServices : $meeting->service->childServices;
        $day = $start->formatLocalized('%A');
        $mes = $start->formatLocalized('%B');
        $title = __('week-days.' . $day) . ' ' . $start->formatLocalized('%d') . ' ' . __('months.' . $mes);

        return VistasGafaFit::view('admin.location.calendar.form', [
            'meeting'        => $meeting,
            'companies_id'   => $company->id,
            'brands_id'      => $brand->id,
            'brandModel'     => $brand,
            'locations_id'   => $location->id,
            'rooms_id'       => $room->id,
            'date'           => $start->format('Y-m-d'),
            'title'          => $title,
            'services'       => LibServices::getParentServices($brand),
            'start_date'     => $meeting->start_date,
            'end_date'       => $meeting->end_date,
            'start_time'     => $start->format('h:ia'),
            'end_time'       => $end->format('h:ia'),
            'child_services' => $child_services,
            'passed'         => $passed,
            'staff'          => Staff::whereHas('brands', function ($q) use ($brand) {
                $q->where('brands.id', $brand->id);
            })->where('status', 'active')->get(),
            'meeting_staff'  => $meeting->staff,
            'serv'           => $meeting->service,
        ]);
    }

    /**
     * Guardar un meeting
     *
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Room     $room
     *
     * @param Meeting  $meeting
     *
     * @return \App\Librerias\Catalog\LibCatalogoModel
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save(Request $request, Company $company, Brand $brand, Location $location, Room $room, Meeting $meeting)
    {
        LibMeeting::getCapacity($request, $room);
        LibMeeting::checkServiceIds($request);
        $saved = CatalogFacade::save($request, CatalogMeeting::class);

        $saved->type = $saved->service->name;
        $saved->title = $saved->staff->name;
        $saved->available = $saved->available();
        $saved->passed = $saved->isPast();

        return $saved;
    }

    /**
     * Guardar un meeting nuevo
     *
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Room     $room
     * @param Meeting  $meeting
     *
     * @return \App\Librerias\Catalog\LibCatalogoModel
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveNew(Request $request, Company $company, Brand $brand, Location $location, Room $room, Meeting $meeting)
    {

        if (is_string($request->get('recurrent'))) {
            $days = LibMeeting::dates_recurrent($request);
            $weekIdx = -1;
            $firstRecurringMeetingId = -1;
            $firstWeekDay = Carbon::createFromFormat('Y-m-d', $days[0])->dayOfWeek;
            $frequency = (int)($request->frecuency);

            foreach ($days as $weekday) {
                if($firstWeekDay == Carbon::createFromFormat('Y-m-d', $weekday)->dayOfWeek){
                    $weekIdx++;
                }

                if ($weekIdx % $frequency == 0) {
                    $startdate = Carbon::parse($weekday . ' ' . $request->get('start_time'));
                    $enddate = Carbon::parse($weekday . ' ' . $request->get('end_time'));

                    $request['start_date'] = $startdate;
                    $request['end_date'] = $enddate;

                    LibMeeting::getCapacity($request, $room);
                    LibMeeting::checkServiceIds($request);
                    $saved = CatalogFacade::save($request, CatalogMeeting::class);

                    if($firstRecurringMeetingId == -1){
                        $firstRecurringMeetingId = $saved->getKey();
                    }

                    $saved->recurring_meeting_id = $firstRecurringMeetingId;
                    $saved->reserve_auto = (int)($request->get('auto-recurrent'));
                    $saved->save();
                }
            }
        } else {
            LibMeeting::getCapacity($request, $room);
            LibMeeting::checkServiceIds($request);
            $saved = CatalogFacade::save($request, CatalogMeeting::class);
        }

        $saved->type = $saved->service->name;
        $saved->title = $saved->staff->name;
        $saved->available = $saved->available();
        $saved->passed = $saved->isPast();

        return $saved;
    }

    /**
     * Borrar un meeting
     *
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Room     $room
     * @param Meeting  $meeting
     *
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function delete(Request $request, Company $company, Brand $brand, Location $location, Room $room, Meeting $meeting)
    {
        if (!$meeting->isPast()) {

            $meeting->cancel(true);
            $id = $meeting->id;
            CatalogFacade::delete($request, CatalogMeeting::class);

            return $id;
        }

        return abort(403);
    }

    /**
     * Trae los servicios subordinados de un servicio
     *
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Room     $room
     * @param          $service_id
     *
     * @return mixed
     */
    public function childServices(Request $request, Company $company, Brand $brand, Location $location, Room $room, int $service_id)
    {
        $service = Service::withTrashed()->where('id', $service_id)->first();

        return $service->childServices->values() ?? null;
    }

    /**
     * Obtiene el staff con el término de búsqueda
     *
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Room     $room
     *
     * @return mixed
     */
    public function staff(Request $request, Company $company, Brand $brand, Location $location, Room $room)
    {
        return LibStaff::getStaff($request, $company);
    }

    /**
     * Endpoint para copiar los meetings dentro de un periodo y los pega una semana después.
     *
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Room     $room
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function repeatWeek(Request $request, Company $company, Brand $brand, Location $location, Room $room)
    {
        return LibCalendar::repeatWeek($request, $room);
    }
}
