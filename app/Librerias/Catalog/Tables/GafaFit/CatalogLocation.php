<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 21/03/2018
 * Time: 11:29 AM
 */

namespace App\Librerias\Catalog\Tables\GafaFit;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Location\locationsRelationship;
use App\Traits\TraitConImagen;
use Carbon\Carbon;
use function foo\func;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class CatalogLocation extends LibCatalogoModel
{
    use TraitConImagen, locationsRelationship, SoftDeletes;

    protected $table = 'locations';

    protected $json = [
        'extra_fields',
    ];

    /**
     * @return string
     */
    public function GetName()
    {
        return 'Locations';
    }

    /**
     * Devuelve los valores a procesar
     * Esto nos va a servir para declarar todas las valicaciones, etiquetas y opciones de salvado
     *
     * @param Request|null $request
     *
     * @return LibValoresCatalogo[]
     */
    public function Valores(Request $request = null)
    {
        $location = $this;
        $active = $location->isActive();

        $location->end_time = \request()->get('end_time') === '00:00' ? '24:00' : \request()->get('end_time');

        $actives = new LibValoresCatalogo($this, __('location.active'), '', [
            'validator'    => '',
            'notOrdenable' => true,

        ]);

        $actives->setGetValueNameFilter(function () use ($active) {
            return $active ?
                '<div class="rooms__status is-success">
                    Activo
                </div>' :
                '<div class="rooms__status is-error">
                    Inactivo
                </div>';
        });

        $botones = new LibValoresCatalogo($this, __('location.edit'), '', [
            'validator'    => '',
            'hiddenInList' => false,
        ]);

        $botones->setGetValueNameFilter(function ($lib, $value) use ($location, $active) {
            return VistasGafaFit::view('admin.brand.locations.botones', [
                'LocationToEdit' => $location,
                'view_route'     => $location->link(),
            ])->render();
        });

        //Guardado del waiver
        if (\request()->has('waiver_forze') && \request()->get('waiver_forze', '') === 'on') {
            $location->waiver_forze = true;
        } else {
            $location->waiver_forze = false;
        }

//        dd($location);

        return [
            new LibValoresCatalogo($this, __('location.Name'), 'name', [
                'validator' => 'required|string|max:100',
            ], function () use ($location, $request) {
                //extras
                if (
                    $request->has('status')
                    &&
                    $request->get('status', '') === 'on'
                ) {
                    $location->status = 'active';

                } else {
                    $location->status = 'inactive';
                }

//                if (
//                    \request()->has('waiver_forze')
//                    &&
//                    \request()->get('waiver_forze', '') === 'on'
//                ) {
//                    $location->waiver_forze = true;
//
//                } else {
//                    $location->waiver_forze = false;
//                }
            }),

            new LibValoresCatalogo($this, '', 'email', [
                'validator'    => 'nullable|email',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'phone', [
                'validator'    => '',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'whatsapp', [
                'validator'    => '',
                'hiddenInList' => true,
            ]),

            new  LibValoresCatalogo($this, '', 'services_id', [
                'validator'    => 'nullable|exists:services,id|integer|min:0',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'order', [
                'validator'    => 'nullable|integer|min:0',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'street', [
                'validator'    => 'nullable|string|max:190',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'number', [
                'validator'    => 'nullable|string|max:190',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'suburb', [
                'validator'    => 'nullable|string|max:190',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'postcode', [
                'validator'    => 'nullable|string|min:0',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'district', [
                'validator'    => 'nullable|string|max:190',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'city', [
                'validator'    => 'string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'countries_id', [
                'validator'    => 'exists:countries,id|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'country_states_id', [
                'validator'    => 'exists:country_states,id|nullable',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'gmaps', [
                'validator'    => 'nullable|string|max:190',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'latitude', [
                'validator'    => 'nullable',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'longitude', [
                'validator'    => 'nullable',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'since', [
                'validator'    => 'nullable|date|required_with:until',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'until', [
                'validator'    => 'nullable|date|after:since|required_with:since',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'date_start', [
                'validator'    => 'nullable|date',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'start_time', [
                'validator'    => 'nullable|date_format:"H:i"',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'end_time', [
                'validator'    => 'nullable|date_format:"H:i"',
                'hiddenInList' => true,
            ]),


            new LibValoresCatalogo($this, '', 'companies_id', [
                'validator'    => 'exists:companies,id',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'brands_id', [
                'validator'    => 'exists:brands,id',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'pic', [
                'validator'    => 'nullable|string|max:190',
                'hiddenInList' => true,
                'type'         => 'file',
            ]),

            new LibValoresCatalogo($this, '', 'waiver_text', [
                'validator'    => 'nullable',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'calendar_days', [
                'validator'    => 'integer|min:7',
                'hiddenInList' => true,
            ]),

            $actives,
            $botones,

        ];
    }

    protected static function filtrarQueries(&$query)
    {
        parent::filtrarQueries($query);

        $query->with(['company', 'brand']);
    }


//    public function link(): string
//    {
//        return VistasGafaFit::view('admin.brand.index');
//    }

    /**
     * Link del modelo
     *
     * @return string
     */
    public function link(): string
    {
        return route('admin.company.brand.locations.dashboard', [
            'company'  => $this->company,
            'brand'    => $this->brand,
            'location' => $this,
        ]);

    }

    public function extenderValidacion(&$validator)
    {
        $request = request();

        $picture = $request->file('pic', null);
        if ($picture) {
            $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];
            $contentType = $picture->getClientMimeType();
            if (!in_array($contentType, $allowedMimeTypes)) {
                $validator->errors()->add('pic', __('validation.image', ['attribute' => __('mails.logo')]));
            }
        }

        $start_time = $request->get('start_time');
        $end_time = $request->get('end_time');
        if ($start_time && $end_time) {
            $start_time = $start_time === '00:00' ? new Carbon('0001-01-01T' . $start_time) : new Carbon('0001-01-02T' . $start_time);
            $end_time = $end_time === '00:00' ? new Carbon('0001-01-03T' . $end_time) : new Carbon('0001-01-02T' . $end_time);
            if ($start_time >= $end_time) {
                $validator->errors()->add('start_time', __('validation.gt', ['attribute' => __('location.start-time'), 'field' => __('location.end-time')]));
            }
        }
    }
}
