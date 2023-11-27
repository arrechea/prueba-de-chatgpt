<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 09/10/2018
 * Time: 03:24 PM
 */

namespace App\Librerias\Catalog\Tables\Brand;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Reservation\ReservationsRelationship;
use Illuminate\Http\Request;

class CatalogReservations extends LibCatalogoModel
{
    use ReservationsRelationship;
    protected $table = 'reservations';

    public function GetName()
    {
        return 'Reservation';
    }

    public function link(): string
    {
        return '';
    }

    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.brand.reservations.filters');
    }

    static protected function filtrarQueries(&$query)
    {
        $brands_id = LibFilters::getFilterValue('brands_id');


        $query->where('brands_id', $brands_id);
        $query->with(['user']);

    }

    static protected function ColumnToSearch()
    {
        $brands_id = LibFilters::getFilterValue('brands_id');

        return function ($query, $criterio) use ($brands_id) {
            $query->where('id', 'like', "%{$criterio}%")
                ->where('brands_id', $brands_id);
            $query->orWhere(function ($q) use ($criterio) {
                $q->whereHas('user', function ($q) use ($criterio) {
                    $q->where('first_name', 'like', "%{$criterio}%");
                    $q->orwhere('last_name', 'like', "%{$criterio}%");
                });
            });

        };
    }

    public function Valores(Request $request = null)
    {
        $reservation = $this;

        return [
            (new LibValoresCatalogo($this, __('reservations.User'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($reservation) {
                return $reservation->user->first_name." ".$reservation->user->last_name;
            }),

            (new LibValoresCatalogo($this, __('reservations.Room'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($reservation) {
                return $reservation->room->name;
            }),

            (new LibValoresCatalogo($this, __('reservations.Status'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($reservation) {

                if ($reservation->iscancelled()) {
                    $status = __('reservations.Cancelled');
                } else if ($reservation->meetings->start_date->isPast() ) {
                    $status = __('reservations.past-reservation');
                } else {
                    $status = __('reservations.future-reservation');
                }

                return $status;
            }),

            (new LibValoresCatalogo($this, __('reservations.Actions'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($reservation) {
                return VistasGafaFit::view('admin.brand.reservations.button', [
                    'reservation' => $reservation,
                ])->render();
            }),
        ];
    }

}
