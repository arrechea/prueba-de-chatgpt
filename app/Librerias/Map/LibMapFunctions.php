<?php


namespace App\Librerias\Map;


use App\Models\Maps\MapsObject;
use App\Models\Reservation\Reservation;

abstract class LibMapFunctions
{
    public static function getPositionText(Reservation $reservation, bool $get_object = false)
    {
        if ($reservation->maps_id && $reservation->meeting_position) {
            $object = MapsObject::where([
                ['maps_id', (int)$reservation->maps_id],
                ['position_number', (int)$reservation->meeting_position],
            ])->get()->first();

            if ($get_object) {
                return $object;
            } else {
                return $object ? $object->position_text : $reservation->meeting_position;
            }
        }
    }
}
