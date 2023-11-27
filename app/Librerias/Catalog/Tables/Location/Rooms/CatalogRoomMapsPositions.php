<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 27/08/2018
 * Time: 11:06 AM
 */

namespace App\Librerias\Catalog\Tables\Location\Rooms;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Maps\MapsRelations;
use App\Traits\TraitConImagen;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class CatalogRoomMapsPositions extends LibCatalogoModel
{
    use MapsRelations, TraitConImagen, SoftDeletes;

    protected $table = 'maps_positions';

    public function link(): string
    {
        return '';
    }

    public function GetName()
    {
        return 'MapsPosition';
    }

    static protected function filtrarQueries(&$query)
    {
        $locations_id = LibFilters::getFilterValue('locations_id');

        $query->where([
            ['locations_id', $locations_id],
        ]);
    }

    public function Valores(Request $request = null)
    {
        $position = $this;

        return [
            new LibValoresCatalogo($this, __('maps.name'), 'name', [
                'validator' => 'string|required',
            ], function () use ($position, $request) {
                //Extras
                if (
                    $request->has('status')
                    &&
                    $request->get('status', '') === 'on'
                ) {
                    $position->status = 'active';
                } else {
                    $position->status = 'inactive';
                }
            }),
            new LibValoresCatalogo($this, __('maps.baseImage'), 'image', [
                'validator' => '',
                'type'      => 'file',
            ]),
            new LibValoresCatalogo($this, '', 'image_selected', [
                'validator'    => '',
                'hiddenInList' => true,
                'type'         => 'file',
            ]),
            new LibValoresCatalogo($this, '', 'type', [
                'validator'    => 'in:public,private,coach',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'image_disabled', [
                'validator'    => '',
                'hiddenInList' => true,
                'type'         => 'file',
            ]),
            new LibValoresCatalogo($this, '', 'width', [
                'validator'    => 'integer|min:1',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'height', [
                'validator'    => 'integer|min:1',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'companies_id', [
                'validator'    => 'required|integer|min:0|exists:companies,id',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'brands_id', [
                'validator'    => 'required|integer|min:0|exists:brands,id',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'locations_id', [
                'validator'    => 'required|integer|min:0|exists:locations,id',
                'hiddenInList' => true,
            ]),
            (new LibValoresCatalogo($this, __('maps.Active'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function ($lib, $value) use ($position) {
                return VistasGafaFit::view('admin.common.check', [
                    'active' => $position->isActive(),
                ])->render();
            }),
            (new LibValoresCatalogo($this, __('maps.Actions'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function ($lib, $value) use ($position) {
                return VistasGafaFit::view('admin.location.rooms.positions.button', [
                    'position' => $position,
                ])->render();
            }),
        ];

    }
}
