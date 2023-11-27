<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 07/08/2018
 * Time: 05:22 PM
 */

namespace App\Librerias\Catalog\Tables\Location\Metrics;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Models\Credit\CreditsUserRelations;
use App\Models\Reservation\ReservationsRelationship;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Models\User\UserProfile;

class CatalogUserCreditsUsed extends LibCatalogoModel
{
    use SoftDeletes, ReservationsRelationship;
    protected $table = 'reservations';
    protected $dates = [
        'expiration_date',
    ];

    public function GetName()
    {
        return 'Reservations';
    }

    static protected function filtrarQueries(&$query)
    {
        $location = LibFilters::getFilterValue('locationFilter');
        /**
         * @var UserProfile $profile
         */
        $profile = LibFilters::getFilterValue('profileFilter');

        $query->where('user_profiles_id', $profile->id)
            ->where('locations_id', $location->id)
            ->select(DB::raw('ANY_VALUE(created_at) as Fecha, 
            ANY_VALUE(id) as reservation_id,
            ANY_VALUE(meeting_start) as meeting_start,
            ANY_VALUE(services_id) as services_id,
            ANY_VALUE(locations_id)as locations_id,
            ANY_VALUE(credits)as credits,
            credits_id as id, 
            credits_id'))
            ->with('credit')
            ->with('location')
            ->with('service');

    }

    public function Valores(Request $request = null)
    {
        $reservation = $this;

        return [
            (new LibValoresCatalogo($this, __('credits.Credit'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($reservation) {
                return $reservation->credit ? $reservation->credit->name : '';
            }),
            (new LibValoresCatalogo($this, __('credits.CreditsNumber'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($reservation) {
                return $reservation->credits;
            }),
            (new LibValoresCatalogo($this, __('credits.study'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($reservation) {
                return $reservation->location->name;
            }),
            (new LibValoresCatalogo($this, __('credits.usedDate'), 'Fecha', [
                'validator' => '',
            ])),
            (new LibValoresCatalogo($this, __('credits.reservationsId'), 'reservation_id', [
                'validator' => '',
            ])),
            (new LibValoresCatalogo($this, __('credits.reservationsStart'), 'meeting_start', [
                'validator' => '',
            ])),
            (new LibValoresCatalogo($this, __('credits.service'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($reservation) {
                return $reservation->service->name;
            }),

        ];

    }

    public function link(): string
    {
        return '';
    }

    protected static function ColumnToSearch()
    {
        return function ($query, $criterio) {
            $query->whereHas('credit', function ($q) use ($criterio) {
                $q->where('name', 'like', "%{$criterio}%");
            });
        };
    }
}
