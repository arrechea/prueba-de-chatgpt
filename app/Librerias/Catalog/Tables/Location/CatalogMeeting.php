<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 16/05/2018
 * Time: 12:10 PM
 */

namespace App\Librerias\Catalog\Tables\Location;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Gympass\Helpers\GympassAPISlotsFunctions;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Models\Catalogs\HasSpecialTexts;
use App\Models\JsonColumns\JsonColumnTrait;
use App\Models\Location\Location;
use App\Models\Meeting\Meeting;
use App\Models\Meeting\MeetingTrait;
use App\Models\Room\Room;
use App\Librerias\Helpers\LibFilters;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CatalogMeeting extends LibCatalogoModel implements HasSpecialTexts
{
    use MeetingTrait, SoftDeletes, JsonColumnTrait;

    protected $table = 'meetings';
    protected $dates = [
        'start_date',
        'end_date',
    ];
    protected $json = [
        'extra_fields',
    ];

    public function GetName()
    {
        return 'Meetings';
    }

    public function link(): string
    {
        return '';
    }

    /**
     * @param Request|null $request
     *
     * @return array
     */
    public function Valores(Request $request = null)
    {
        $meeting = $this;

        return [
            new LibValoresCatalogo($this, '', 'companies_id', [
                'validator' => 'required|integer|min:0|exists:companies,id',
            ]),
            new LibValoresCatalogo($this, '', 'brands_id', [
                'validator' => 'required|integer|min:0|exists:brands,id',
            ]),
            new LibValoresCatalogo($this, '', 'locations_id', [
                'validator' => 'required|integer|min:0|exists:locations,id',
            ]),
            new LibValoresCatalogo($this, '', 'rooms_id', [
                'validator' => 'required|integer|min:0|exists:rooms,id',
            ]),
            new LibValoresCatalogo($this, '', 'staff_id', [
                'validator' => 'required|integer|min:0|exists:staff,id',
            ]),
            new LibValoresCatalogo($this, '', 'services_id', [
                'validator' => 'required|integer|min:0|exists:services,id',
            ], function () use ($request, $meeting) {
                //Extras
                if (LibPermissions::userCan(\Auth::user(), LibListPermissions::GYMPASS_SLOT_EDIT, $meeting->location)) {
                    if (
                        $request->has('gympass_active')
                        &&
                        $request->get('gympass_active', '') === 'on'
                    ) {
                        $meeting->setDotValue('extra_fields.gympass.active', 1);
                    } else {
                        $meeting->setDotValue('extra_fields.gympass.active', 0);
                    }
                }
            }),
            new LibValoresCatalogo($this, '', 'description', [
                'validator' => 'nullable',
            ]),
            new LibValoresCatalogo($this, '', 'color', [
                'validator' => 'string|max:190|nullable',
            ]),
            new LibValoresCatalogo($this, '', 'start_date', [
                'validator' => 'required|date',
            ]),
            new LibValoresCatalogo($this, '', 'end_date', [
                'validator' => 'required|date|after:start_date',
            ]),
            new LibValoresCatalogo($this, '', 'capacity', [
                'validator' => 'integer|min:0|nullable',
            ]),
            new LibValoresCatalogo($this, '', 'details', [
                'validator' => 'nullable|in:quantity,map',
            ]),
            new LibValoresCatalogo($this, '', 'maps_id', [
                'validator'    => 'nullable|exists:maps,id',
                'hiddenInList' => true,
            ]),
        ];
    }

    /**
     * @param $query
     */
    static protected function filtrarQueries(&$query)
    {
        $locationId = LibFilters::getFilterValue('locations_id');

        $query->where('locations_id', $locationId);
    }

    protected function extenderValidacion(&$validator)
    {
        $location = \request()->route('location');
        $start_date = (new Carbon(\request('start_date')))->format('H:i');
        $end_date = (new Carbon(\request('end_date')))->format('H:i');
        $start_time = $location->start_time;
        $end_time = $location->end_time;

        if (!!$start_time && !!$end_time) {
            if ($start_date < $start_time || $start_date > $end_time || $end_date < $start_time || $end_date > $end_time) {
                $validator->errors()->add('opening_hours', __('calendar.out-of-hours-error'));
            }
        }
    }

    public function runLastSave()
    {
        $request = \request();
        if ($this->isGympassActive()) {
            $validator = \Validator::make([], []);
            $meeting = Meeting::find($this->id);

            if ($request->filled('gympass_regenerate_slot') && $request->get('gympass_regenerate_slot') === 'on') {
                $response = GympassAPISlotsFunctions::regenerateSlotID($meeting, $validator);
                \Log::info(json_encode($response));
            } else {
                $response = GympassAPISlotsFunctions::saveSlotFromMeeting($meeting, $validator);
                \Log::info(json_encode($response));
            }


            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        }
    }
}
