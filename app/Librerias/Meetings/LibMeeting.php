<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 21/05/2018
 * Time: 04:50 PM
 */

namespace App\Librerias\Meetings;

use App\Http\Requests\AdminRequest as Request;
use App\Models\Room\Room;
use Carbon\Carbon;

class LibMeeting
{
    public static function checkServiceIds(Request &$request)
    {
        $child_id = $request->get('child_id', 0);
        if ($child_id) {
            $request->merge([
                'services_id' => $child_id,
            ]);
        }

        return $request;
    }

    public static function getCapacity(Request &$request, Room $room)
    {
        $capacity = $room->capacity ?? 0;
        $details = $room->details ?? null;
        $maps_id = $room->maps_id ?? null;

        $request->merge([
            'capacity' => $capacity,
            'details'  => $details,
            'maps_id'  => $maps_id,
        ]);

        return $request;
    }

    public static function dates_recurrent(Request &$request)
    {
        $week_days= $request->get('week_day');
        $days = Array();
        foreach($week_days as $day){
            if($day === 'monday'){
                $monday_day = Carbon::MONDAY;
                $startDate = Carbon::parse($request->get('recurrent_from'))->next($monday_day);
                $endDate = Carbon::parse($request->get('recurrent_to'));

                for ($date = $startDate; $date->lte($endDate); $date->addWeek()) {
                    $days[] = $date->format('Y-m-d');
                }
            }

            if($day === 'tuesday'){
                $tuesday_day = Carbon::TUESDAY;
                $startDate = Carbon::parse($request->get('recurrent_from'))->next($tuesday_day);
                $endDate = Carbon::parse($request->get('recurrent_to'));

                for ($date = $startDate; $date->lte($endDate); $date->addWeek()) {
                    $days[] = $date->format('Y-m-d');
                }
            }
            if($day === 'wednesday'){
                $wednesday_day = Carbon::WEDNESDAY;
                $startDate = Carbon::parse($request->get('recurrent_from'))->next($wednesday_day);
                $endDate = Carbon::parse($request->get('recurrent_to'));

                for ($date = $startDate; $date->lte($endDate); $date->addWeek()) {
                    $days[] = $date->format('Y-m-d');
                }
            }
            if($day === 'thursday'){
                $thursday_day = Carbon::THURSDAY;
                $startDate = Carbon::parse($request->get('recurrent_from'))->next($thursday_day);
                $endDate = Carbon::parse($request->get('recurrent_to'));

                for ($date = $startDate; $date->lte($endDate); $date->addWeek()) {
                    $days[] = $date->format('Y-m-d');
                }
            }
            if($day === 'friday'){
                $friday_day = Carbon::FRIDAY;
                $startDate = Carbon::parse($request->get('recurrent_from'))->next($friday_day);
                $endDate = Carbon::parse($request->get('recurrent_to'));

                for ($date = $startDate; $date->lte($endDate); $date->addWeek()) {
                    $days[] = $date->format('Y-m-d');
                }
            }
            if($day === 'saturday'){
                $saturday_day = Carbon::SATURDAY;
                $startDate = Carbon::parse($request->get('recurrent_from'))->next($saturday_day);
                $endDate = Carbon::parse($request->get('recurrent_to'));

                for ($date = $startDate; $date->lte($endDate); $date->addWeek()) {
                    $days[] = $date->format('Y-m-d');
                }
            }
            if($day === 'sunday'){
                $sunday_day = Carbon::SUNDAY;
                $startDate = Carbon::parse($request->get('recurrent_from'))->next($sunday_day);
                $endDate = Carbon::parse($request->get('recurrent_to'));

                for ($date = $startDate; $date->lte($endDate); $date->addWeek()) {
                    $days[] = $date->format('Y-m-d');
                }
            }

        }

        usort($days, function($a1, $a2) {
            return strtotime($a1) - strtotime($a2);
        });
        return $days;
    }
}
