<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 29/08/2018
 * Time: 04:17 PM
 */

namespace App\Librerias\Catalog\Tables\Location\Rooms;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Meeting\MeetingTrait;
use Illuminate\Http\Request;

class CatalogRoomMeetingNoReservation extends LibCatalogoModel
{
    use MeetingTrait;
    protected $table = 'meetings';

    public function link(): string
    {
     return '';
    }

    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.location.rooms.info');
    }

    public function GetName()
    {
        return 'Meeting';
    }


    static protected function filtrarQueries(&$query)
    {
        $locations_id = LibFilters::getFilterValue('locationFilter');
        $room = LibFilters::getFilterValue('roomFilter');

        $now = $room->now();

        $query
            ->with('service')
            ->with('staff')
            ->with('map')
            ->where(function ($q) use ($room) {
                $q
                    ->where('details', '!=', $room->details)
                    ->orWhere('capacity', '!=', $room->capacity)
                    ->orWhere('maps_id', '!=', $room->maps_id);
            })
            ->where('start_date', '>', $now)
            ->where('rooms_id', $room->id)
            ->doesnthave('reservation')
            ->whereNull('deleted_at')
            ->where('locations_id', $locations_id->id);

    }
    public function Valores(Request $request = null)
    {
        $room_meeting = $this;

        return [
            (new LibValoresCatalogo($this, __('reservations.Class'), '', [
                'notOrdenable' => true,
            ]))->setGetValueNameFilter(function () use ($room_meeting) {
                return $room_meeting->service->name;
            }),
            (new LibValoresCatalogo($this, __('reservations.Staff'), '', [
                'notOrdenable' => true,
            ]))->setGetValueNameFilter(function () use ($room_meeting) {
                return $room_meeting->staff->name;
            }),
            (new LibValoresCatalogo($this, __('maps.Map'), '', [

            ]))->setGetValueNameFilter(function () use ($room_meeting) {
                return $room_meeting->name;
            }),

            (new LibValoresCatalogo($this, __('reservations.Reservations'), '', [
                'notOrdenable' => true,
            ]))->setGetValueNameFilter(function () use ($room_meeting) {
                $reservas = $room_meeting->reservation->count();

                return $reservas . '/' . $room_meeting->capacity;
            }),
            (new LibValoresCatalogo($this,__('reservations.Actions'),'',[
                'notOrdenable' => true,
            ]))->setGetValueNameFilter(function ($lib, $value) use ($room_meeting){
                return VistasGafaFit::view('admin.location.rooms.buttons.refresh',[
                    'meeting' => $room_meeting,
                ])->render();
                // todo botones de actualizar o borrar en el form va los de forma global
            })
        ];
    }

}
