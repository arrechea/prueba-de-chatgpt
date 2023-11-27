<?php

namespace App\Librerias\Catalog\Tables\Location;

use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Gympass\Helpers\GympassAPICheckinFunctions;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\GympassCheckin\GympassCheckinTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CatalogGympassCheckin extends LibCatalogoModel
{
    use GympassCheckinTrait;

    protected $table = 'gympass_checkin';

    public function GetName()
    {
        return __('gympass.checkinTitle');
    }

    public function link(): string
    {
        return '';
    }

    public function Valores(Request $request = null)
    {
        $checkin = $this;

        return [
            (new LibValoresCatalogo($this, __('gympass.checkinStatus'), 'status', [
                'validator' => 'required|in:approved,rejected,pending',
            ]))->setGetValueNameFilter(function () use ($checkin) {
                return __('gympass.checkinStatus.' . $checkin->status);
            }),
            (new LibValoresCatalogo($this, __('gympass.checkinUser'), ''))
                ->setGetValueNameFilter(function () use ($checkin) {
                    return ($checkin->user->first_name ?? '') . ' ' . ($checkin->user->last_name ?? '');
                }),
            (new LibValoresCatalogo($this, __('gympass.checkinUserEmail'), ''))
                ->setGetValueNameFilter(function () use ($checkin) {
                    return $checkin->user->email ?? '';
                }),
            (new LibValoresCatalogo($this, __('gympass.Errors'), ''))
                ->setGetValueNameFilter(function () use ($checkin) {
                    return $checkin->errors ? $checkin->errors : '';
                }),
            (new LibValoresCatalogo($this, __('gympass.checkinCreationDate'), ''))
                ->setGetValueNameFilter(function () use ($checkin) {
                    return $checkin->created_at ? (new Carbon($checkin->request_time))->format('Y-m-d H:i') : '';
                }),
            (new LibValoresCatalogo($this, __('gympass.checkinValidationDate'), ''))
                ->setGetValueNameFilter(function () use ($checkin) {
                    return $checkin->response_time ? (new Carbon($checkin->response_time))->format('Y-m-d H:i') : '';
                }),
            (new LibValoresCatalogo($this, __('gympass.Actions'), ''))
                ->setGetValueNameFilter(function () use ($checkin) {
                    return VistasGafaFit::view('admin.location.gympass_checkin.actions', [
                        'checkin' => $checkin,
                    ])->render();
                }),
        ];
    }

    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.location.gympass_checkin.filters');
    }

    public function GetAllowStatusSelector()
    {
        return false;
    }

    protected static function ColumnToSearch()
    {
        return function (&$query, $criterio) {
            $query->whereHas('user', function ($q) use ($criterio) {
                if (is_numeric($criterio)) {
                    $q->where('id', $criterio);
                } else {
                    $q->where('email', 'like', "$criterio%");
                    $q->orWhere(function ($q) use ($criterio) {
                        $q->where('first_name', 'like', "%$criterio%");
                        $q->orWhere('last_name', 'like', "%$criterio%");
                    });
                }
            });
        };
    }

    protected static function filtrarQueries(&$query)
    {
        $locationId = LibFilters::getFilterValue('locations_id');

        $status = LibFilters::getFilterValue('gympass_status', null, null);
        if ($status) {
            $query->where('status', $status);
        }

        $query->where('status', '<>', GympassAPICheckinFunctions::BOOKING_STATUS);

        $query->where('locations_id', $locationId);
    }

    protected static function QueryToOrderBy()
    {
        return 'created_at';
    }
}
