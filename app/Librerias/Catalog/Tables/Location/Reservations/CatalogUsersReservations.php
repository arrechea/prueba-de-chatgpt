<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 15/05/2018
 * Time: 01:21 PM
 */

namespace App\Librerias\Catalog\Tables\Location\Reservations;

use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Catalog\Tables\Company\CatalogUserProfile;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Reservation\ReservationsRelationship;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CatalogUsersReservations extends CatalogUserProfile
{
    use ReservationsRelationship, SoftDeletes;

    /**
     * @param Request|null $request
     *
     * @return array
     */
    public function Valores(Request $request = null)
    {
        $profile = $this;

        return [
            (new LibValoresCatalogo($this, __('users.Name'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($profile) {
                return $profile->first_name . ' ' . $profile->last_name;
            }),
            new LibValoresCatalogo($this, __('users.Email'), 'email', [
                'validator' => '',
            ]),
            (new LibValoresCatalogo($this, __('users.Actions'), '', [
                'validator'    => '',
                'notOrdenable' => true,
            ]))->setGetValueNameFilter(function ($lib) use ($profile) {

                return VistasGafaFit::view('admin.location.reservations.users.button', [
                    'profile' => $profile,
                ])->render();
            }),
        ];
    }

    /**
     * Crea el filtro por compañía
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.location.reservations.users.info');
    }

    /**
     * Filtro por reservas que posea el usuario
     *
     * @param $query
     */
    static protected function filtrarQueries(&$query)
    {
        $locationId = LibFilters::getFilterValue('locations_id');

        $query->whereHas('reservations', function ($query) use ($locationId) {
            $query->where('reservations.locations_id', (int)$locationId);
        });
    }

    protected static function ColumnToSearch()
    {
        $locations_id = LibFilters::getFilterValue('locations_id');

        return function ($query, $criterio) use ($locations_id) {
            $query->where(function ($query) use ($criterio) {
                $query->where('first_name', 'like', "%{$criterio}%");
                $query->orWhere('last_name', 'like', "%{$criterio}%");
                $query->orWhere('email', 'like', "%{$criterio}%");
            });

            $query->whereHas('reservations', function ($query) use ($locations_id) {
                $query->where('reservations.locations_id', (int)$locations_id);
            });
        };
    }
}
