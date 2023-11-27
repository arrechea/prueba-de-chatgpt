<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 15/05/2018
 * Time: 01:23 PM
 */

namespace App\Librerias\Catalog\Tables\Location\Reservations;


use App\Librerias\Catalog\Tables\Location\CatalogRoom;
use App\Librerias\Helpers\LibFilters;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Vistas\VistasGafaFit;

class CatalogRoomReservations extends CatalogRoom
{
    public function Valores(Request $request = null)
    {
        $room = $this;
        $active = $room->isActive();

        $actives = new LibValoresCatalogo($this, __('gafacompany.Active'), '', [
            'validator' => '',
        ]);
        $actives->setGetValueNameFilter(function () use ($active) {
            return $active ?
                '<svg height="20" width="50">
                <circle cx="25" cy="10" r="7" stroke="black" stroke-width="2" fill="green" />
                </svg>' :
                '<svg height="20" width="50">
                <circle cx="25" cy="10" r="7" stroke="black" stroke-width="2" fill="red" />
                </svg>';
        });

        $botones = new LibValoresCatalogo($this, __('staff.Reservations'), '', [
            'validator'    => '',
            'notOrdenable' => true,
        ]);
        $botones->setGetValueNameFilter(function ($lib, $value) use ($room) {
            return VistasGafaFit::view('admin.location.reservations.room.button', [
                'id'         => $room->id,
                'room'       => $room,
                'view_route' => $room->link(),
            ])->render();
        });

        return [
            new LibValoresCatalogo($this, __('company.Name'), 'name', [
                'validator' => 'required|string|max:190',
            ], function () use ($room, $request) {
                if (
                    $request->has('status')
                    &&
                    $request->get('status', '') === 'on'
                ) {
                    $room->status = 'active';
                } else {
                    $room->status = 'inactive';
                }
            }),

            $actives, $botones,

        ];
    }

    static protected function filtrarQueries(&$query)
    {
        $request = \request();

        if ($request->has('filters')) {
            $locations_id = LibFilters::getFilterValue('locations_id',$request);

            $query->where('locations_id', (int)$locations_id);
        } else {
            $query->whereNull('id');
        }
    }
}
