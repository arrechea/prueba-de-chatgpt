<?php

namespace App\Http\Controllers\Api\Meeting;

use App\Http\Controllers\ApiController;
use App\Http\Requests\ApiRequest as Request;
use App\Librerias\Calendars\LibCalendar;
use App\Models\Brand\Brand;
use App\Models\Location\Location;
use App\Models\Room\Room;

class MeetingController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
        $company = $this->getCompany();

        $this->middleware(function ($request, $next) use ($company) {
            $room = $request->route('room');
            $location = $request->route('locationToSee');
            if ($room->locations_id !== (int)$location) {
                abort(404);
            }

            return $next($request);
        })->only([
            'roomMeetings',
        ]);
    }

    /**
     * @param Request $request
     *
     * @param Brand   $brand
     *
     * @return \Illuminate\Http\JsonResponse
     * @internal param Brand $brand
     *
     */
    public function index(Request $request, Brand $brand, $locationToSee)
    {
        $reducePopulation = $request->get('reducePopulation', false) === 'true';
        $events = LibCalendar::getLocationMeetings($request, $locationToSee, $request->get('start'), $request->get('end'), $request->get('only_actives'), true, $reducePopulation);

        return response()->json($events);
    }

    /**
     * @param Request  $request
     * @param Brand    $brand
     * @param Location $locationToSee
     * @param int      $room
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function roomMeetings(Request $request, Brand $brand, $locationToSee, Room $room)
    {
        $reducePopulation = $request->get('reducePopulation', false) === 'true';
        $events = LibCalendar::getMeetings($request, $room->id, $request->get('start'), $request->get('end'), true, $reducePopulation);

        return response()->json($events);
    }
}
