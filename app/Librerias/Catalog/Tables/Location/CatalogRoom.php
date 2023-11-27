<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 04/05/2018
 * Time: 08:46 AM
 */

namespace App\Librerias\Catalog\Tables\Location;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Location\locationsRelationship;
use App\Traits\TraitConImagen;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CatalogRoom extends LibCatalogoModel
{
    use TraitConImagen, SoftDeletes, RoomRelationship;
    protected $table = 'rooms';

    public function GetName()
    {
        return 'Room';
    }

    public function Valores(Request $request = null)
    {
        $room = $this;

        $buttons = new LibValoresCatalogo($this, __('rooms.Actions'), '', [
            'validator' => '',
        ]);
        $buttons->setGetValueNameFilter(function () use ($room) {
            return VistasGafaFit::view('admin.location.rooms.buttons-dashboard', [
                'room' => $room,
            ])->render();
        });

        $status = new LibValoresCatalogo($this, __('gafacompany.Active'), '', [
            'validator' => '',
        ]);
        $status->setGetValueNameFilter(function () use ($room) {
            return VistasGafaFit::view('admin.catalog.status-column-dashboard', ['model' => $room])->render();
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
                //Clear maps_id
                if (
                    $request->get('details') !== 'map'
                ) {
                    $room->maps_id = null;
                }
            }),


            new LibValoresCatalogo($this, '', 'capacity', [
                'validator'    => 'required|integer',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'maps_id', [
                'validator'    => 'nullable|exists:maps,id',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'companies_id', [
                'validator'    => 'integer|exists:companies,id',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'brands_id', [
                'validator'    => 'integer|exists:brands,id',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'locations_id', [
                'validator'    => 'integer|exists:locations,id',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'pic', [
                'validator'    => '',
                'hiddenInList' => true,
                'type'         => 'file',
            ]),

            new LibValoresCatalogo($this, '', 'details', [
                'validator'    => 'nullable|in:quantity,map',
                'hiddenInList' => true,
            ]),
            $status,
            $buttons,
        ];
    }

    public function link(): string
    {
        return route('admin.company.brand.locations.rooms.edit', [
            'company'  => $this->company,
            'brand'    => $this->brand,
            'location' => $this->location,
            'room'     => $this,
        ]);
    }


    static protected function filtrarQueries(&$query)
    {
        $request = \request();

        if ($request->has('filters')) {
            $locations_id = LibFilters::getFilterValue('locations_id', $request);
            $brands_id = LibFilters::getFilterValue('brands_id', $request);

            $query->where('locations_id', (int)$locations_id);
            $query->where('brands_id', (int)$brands_id);
        } else {
            $query->whereNull('id');
        }
    }

    public function GetHtmlExtraEnHeaderIndex()
    {
        return VistasGafaFit::view('admin.location.rooms.info');
    }

    protected function extenderValidacion(&$validator)
    {
        $request = request();

        $picture = $request->file('pic', null);
        if ($picture) {
            $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];
            $contentType = $picture->getClientMimeType();
            if (!in_array($contentType, $allowedMimeTypes)) {
                $validator->errors()->add('pic', __('validation.image', ['attribute' => __('rooms.ProfilePicture')]));
            }
        }
    }
}
