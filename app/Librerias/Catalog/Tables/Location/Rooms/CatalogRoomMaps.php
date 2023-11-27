<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 27/08/2018
 * Time: 11:05 AM
 */

namespace App\Librerias\Catalog\Tables\Location\Rooms;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Maps\MapsRelations;
use App\Traits\TraitConImagen;
use Illuminate\Http\Request;

class CatalogRoomMaps extends LibCatalogoModel
{
    use MapsRelations, TraitConImagen;
    protected $table = 'maps';

    public function link(): string
    {
        return '';
    }

    public function GetName()
    {
        return 'Maps';
    }

    public function Valores(Request $request = null)
    {
        $maps = $this;

        return [
            (new LibValoresCatalogo($this, __('maps.Active'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function ($lib, $value) use ($maps) {
                return VistasGafaFit::view('admin.common.check', [
                    'active' => $maps->isActive(),
                ])->render();
            }),
            new LibValoresCatalogo($this, __('maps.name'), 'name', [
                'validator' => 'string',
            ], function () use ($maps, $request) {
                if (
                    $request->has('status')
                    &&
                    $request->get('status', '') === 'on'
                ) {
                    $maps->status = 'active';
                } else {
                    $maps->status = 'inactive';
                }
            }),
            //columna de salon.
            (new LibValoresCatalogo($this, __('maps.capacity'), '', [
                'validator' => 'integer|min:1',
            ]))->setGetValueNameFilter(function ($lib, $value) use ($maps) {
                return $maps->capacity;
            }),
            (new LibValoresCatalogo($this, __('maps.rows'), '', [
                'validator' => 'integer|min:1',
            ]))->setGetValueNameFilter(function ($lib, $value) use ($maps) {
                return $maps->rows;
            }),
            (new LibValoresCatalogo($this, __('maps.columns'), '', [
                'validator' => 'integer|min:1',
            ]))->setGetValueNameFilter(function ($lib, $value) use ($maps) {
                return $maps->columns;
            }),
            new LibValoresCatalogo($this, __('maps.image'), 'image_background', [
                'validator' => '',
                'type'      => 'file',
            ]),
            (new LibValoresCatalogo($this, __('maps.Actions'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function ($lib, $value) use ($maps) {
                return VistasGafaFit::view('admin.location.rooms.maps.button', [
                    'maps' => $maps,
                ])->render();
            }),
        ];
    }

    /**
     * @param $validator
     */
    protected function extenderValidacion(&$validator)
    {
        $request = request();

        $picture = $request->file('image_background', null);
        if ($picture) {
            $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];
            $contentType = $picture->getClientMimeType();
            if (!in_array($contentType, $allowedMimeTypes)) {
                $validator->errors()->add('pic', __('validation.image', ['attribute' => __('users.ProfilePicture')]));
            }
        }
    }

    static protected function filtrarQueries(&$query)
    {
        $locations_id = LibFilters::getFilterValue('locations_id');

        $query->where([
            ['locations_id', $locations_id],
        ]);
    }
}
