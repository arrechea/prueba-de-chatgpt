<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 21/03/2018
 * Time: 10:35 AM
 */

namespace App\Librerias\Catalog\Tables\GafaFit;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Time\LibTime;
use App\Models\Brand\BrandRelationships;
use App\Models\Catalogs\HasSpecialTexts;
use App\Traits\TraitConImagen;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class CatalogBrand extends LibCatalogoModel implements HasSpecialTexts
{
    use SoftDeletes, BrandRelationships, TraitConImagen;
    protected $table = 'brands';
    protected $json=['extra_fields'];

    /**
     * @return string
     */
    public function GetName()
    {
        return 'Brands';
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
        $gafaBrand = $this;

        $allowed = \request()->get('has_mail_permission', false);

        $mail_from = new LibValoresCatalogo($this, '', 'mail_from', [
            'validator'    => 'email|nullable',
            'hiddenInList' => true,
        ]);

        $name_from = new LibValoresCatalogo($this, '', 'name_from', [
            'validator'    => 'string|nullable',
            'hiddenInList' => true,
        ]);
//        dd($gafaBrand,\request());

        $response = [
            new LibValoresCatalogo($this, __('brand.Name'), 'name', [
                'validator' => 'required|string|max:100',
            ], function () use ($gafaBrand, $request) {
                //Extras
                if (
                    $request->has('status')
                    &&
                    $request->get('status', '') === 'on'
                ) {
                    $gafaBrand->status = 'active';
                } else {
                    $gafaBrand->status = 'inactive';
                }

                if (
                    $request->has('waiver_forze')
                    &&
                    $request->get('waiver_forze', '') === 'on'
                ) {
                    $gafaBrand->waiver_forze = true;
                } else {
                    $gafaBrand->waiver_forze = false;
                }

                if (
                    $request->has('time_format')
                    &&
                    $request->get('time_format', '') === 'on'
                ) {
                    $gafaBrand->time_format = '24';
                } else {
                    $gafaBrand->time_format = '12';
                }

                if (
                    $request->has('waitlist')
                    &&
                    $request->get('waitlist', '') === 'on'
                ) {
                    $gafaBrand->waitlist = true;
                } else {
                    $gafaBrand->waitlist = false;
                }
            }),

            new LibValoresCatalogo($this, '', 'email', [
                'validator'    => [
                    'nullable',
                    'email',
                ],
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'city', [
                'validator'    => 'string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'countries_id', [
                'validator'    => 'nullable|exists:countries,id',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'country_states_id', [
                'validator'    => 'exists:country_states,id|nullable',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'currencies_id'
                , [
                    'validator'    => 'exists:currencies,id',
                    'hiddenInList' => true,
                ]),

            new LibValoresCatalogo($this, '', 'language_id', [
                'validator'    => 'exists:language,id',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'street', [
                'validator'    => 'nullable|string|max:190',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'external_number', [
                'validator'    => 'nullable|string|max:190',
                'hiddenInList' => true,
            ]),

            new  LibValoresCatalogo($this, '', 'suburb', [
                'validator'    => 'nullable|string|max:190',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'postcode', [
                'validator'    => 'nullable|string|max:190',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'district', [
                'validator'    => 'nullable|string|max:190',
                'hiddenInList' => true,
            ]),

//            new LibValoresCatalogo($this, '', 'state', [
//                'validator'    => 'nullable|string|max:190',
//                'hiddenInList' => true,
//            ]),

            new LibValoresCatalogo($this, '', 'pic', [
                'validator'    => '',
                'hiddenInList' => true,
                'type'         => 'file',
            ]),

            new LibValoresCatalogo($this, '', 'terms_conditions_link', [
                'validator'    => 'url|nullable',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'banner', [
                'validator'    => '',
                'hiddenInList' => true,
                'type'         => 'file',
            ]),


            new LibValoresCatalogo($this, '', 'companies_id', [
                'validator'    => 'exists:companies,id',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'job', [
                'validator'    => 'string|nullable',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'description', [
                'validator'    => 'string|nullable|max:6000',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'cancelation_dead_line', [
                'validator'    => 'integer|nullable',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'waiver_text', [
                'validator'    => 'nullable',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'copyright', [
                'validator'    => 'string|nullable',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'facebook', [
                'validator'    => 'url|nullable',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'youtube', [
                'validator'    => 'url|nullable',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'instagram', [
                'validator'    => 'url|nullable',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'twitter', [
                'validator'    => 'url|nullable',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'google_plus', [
                'validator'    => 'url|nullable',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'snapchat', [
                'validator'    => 'url|nullable',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'spotify', [
                'validator'    => 'url|nullable',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'tumblr', [
                'validator'    => 'url|nullable',
                'hiddenInList' => true,
            ]),

            new LibValoresCatalogo($this, '', 'linkedin', [
                'validator'    => 'url|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'time_zone', [
                'validator'    => LibTime::getTimeZoneValidator(),
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'pinterest', [
                'validator'    => 'url|nullable',
                'hiddenInList' => true,
            ]),
            'name_from' => $name_from,
            'mail_from' => $mail_from,
            new LibValoresCatalogo($this, '', 'explanation_waitlist', [
                'validator'    => 'string|nullable|max:6000',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'max_waitlist', [
                'validator'    => 'numeric|min:0',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'simultaneous_reservations', [
                'validator'    => 'required|numeric|min:1',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'map_css', [
                'type'         => 'select',
                'validator'    => 'required|in:reservation-template.css,reservation.css',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'extra_fields.require_guest_info', [
                'type'         => 'checkbox',
                'validator'    => 'nullable|in:on',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'no_availability_display', [
                'validator'    => 'string|in:default,disable|nullable',
                'hiddenInList' => true,
                'default'      => 'default',
            ]),
        ];

        if (!$allowed) {
            unset($response['mail_from']);
            unset($response['name_from']);
        }

        return $response;
    }

    /**
     * @param $validator
     */
    public function extenderValidacion(&$validator)
    {
        $request = request();
        $compania = $request->route('company');

        if (!isset($compania))
            $compania = $request->company;

        $picture = $request->file('pic', null);
        if ($picture) {
            $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];
            $contentType = $picture->getClientMimeType();
            if (!in_array($contentType, $allowedMimeTypes)) {
                $validator->errors()->add('pic', __('validation.image', ['attribute' => __('mails.logo')]));
            }
        }

        $picture = $request->file('banner', null);
        if ($picture) {
            $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];
            $contentType = $picture->getClientMimeType();
            if (!in_array($contentType, $allowedMimeTypes)) {
                $validator->errors()->add('pic', __('validation.image', ['attribute' => __('brand.Banner')]));
            }
        }


        if ((int)($compania->id) !== (int)($request->get('companies_id', 0))) {
            $validator->errors()->add('companies_id', 'test errors');
        }
    }

    /**
     * Link del modelo
     *
     * @return string
     */
    public function link(): string
    {
        return route('admin.company.brand.dashboard', [
            'company' => $this->company,
            'brand'   => $this,
        ]);
    }

    protected static function filtrarQueries(&$query)
    {
        parent::filtrarQueries($query);

        $query->with('company');
    }


}
