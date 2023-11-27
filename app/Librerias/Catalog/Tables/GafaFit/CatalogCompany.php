<?php

namespace App\Librerias\Catalog\Tables\GafaFit;


use App\Events\Gympass\TriggerGympassApprovalEmail;
use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Gympass\Helpers\GympassHelpers;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Company\Company;
use App\Models\Company\CompanyRelations;
use App\Models\JsonColumns\JsonColumnTrait;
use App\Observers\CompanyObserver;
use App\Traits\TraitConImagen;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CatalogCompany extends LibCatalogoModel
{
    use SoftDeletes, CompanyRelations, JsonColumnTrait, TraitConImagen;

    protected $table = 'companies';
    protected $json = ['extra_fields'];

    protected static function boot()
    {
        parent::boot();

        static::observe(CompanyObserver::class);
    }

    /**
     * @return string
     */
    public function GetName()
    {
        return 'Companies';
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
        $gafacompany = $this;
        $active = $gafacompany->isActive();

        $actives = new LibValoresCatalogo($this, __('gafacompany.Active'), '', [
            'validator' => '',
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

        $botones = new LibValoresCatalogo($this, __('gafacompany.Edit'), '', [
            'validator'    => '',
            'notOrdenable' => true,
        ]);
        $botones->setGetValueNameFilter(function ($lib, $value) use ($gafacompany, $active) {


            return VistasGafaFit::view('admin.gafafit.companies.botones', [
                'id'         => $gafacompany->id,
                'view_route' => $gafacompany->link(),
                'active'     => $active,
            ])->render();
        });

        $allowed = \request()->get('has_mail_permission', false);

        $mail_from = new LibValoresCatalogo($this, '', 'mail_from', [
            'validator'    => 'email|nullable',
            'hiddenInList' => true,
        ]);

        $name_from = new LibValoresCatalogo($this, '', 'name_from', [
            'validator'    => 'string|nullable',
            'hiddenInList' => true,
        ]);


        $response = [
            new LibValoresCatalogo($this, __('company.Name'), 'name', [
                'validator' => 'required|string|max:100',
            ], function () use ($gafacompany, $request) {
                //Extras
                if (
                    $request->has('status')
                    &&
                    $request->get('status', '') === 'on'
                ) {
                    $gafacompany->status = 'active';
                } else {
                    $gafacompany->status = 'inactive';
                }
            }),
            new LibValoresCatalogo($this, '', 'email', [
                'validator'    => [
                    'nullable',
                    'email',
                ],
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'slug', [
                'validator'    => '',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'admins_id', [
                'validator'    => 'nullable|exists:admins,id',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'countries_id', [
                'validator'    => 'nullable|exists:countries,id',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'address', [
                'validator'    => 'nullable|string|max:190',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'copyright', [
                'validator'    => 'nullable|string|max:190',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'external_number', [
                'validator'    => 'nullable|string|max:190',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'municipality', [
                'validator'    => 'nullable|string|max:190',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'postal_code', [
                'validator'    => 'nullable|string|max:190',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'city', [
                'validator'    => 'string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'country_states_id', [
                'validator'    => 'exists:country_states,id|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'language_id', [
                'validator'    => 'exists:language,id|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'pic', [
                'validator'    => '',
                'hiddenInList' => true,
                'type'         => 'file',
            ]),
            'mail_from'         => $mail_from,
            'name_from'         => $name_from,
            $actives,
            $botones,
            'mailchimp_apikey'  => new LibValoresCatalogo($this, '', 'mailchimp_apikey', [
                'validator'    => 'string|nullable',
                'hiddenInList' => true,
            ]),
            'mailchimp_list_id' => new LibValoresCatalogo($this, '', 'mailchimp_list_id', [
                'validator'    => 'string|nullable',
                'hiddenInList' => true,
            ]),
        ];

        if (
            GympassHelpers::isGympassActive() &&
            LibPermissions::userCan(Auth::user(), LibListPermissions::GYMPASS_SETTINGS_EDIT)
        ) {
            $gympass = new LibValoresCatalogo($this, '', 'extra_fields.gympass.gym_id', [
                'validator'    => 'integer|nullable',
                'hiddenInList' => true,
            ], function () use ($gafacompany, $request) {
                //Extras
                if (
                    $request->has('gympass_active')
                    &&
                    $request->get('gympass_active', '') === 'on'
                ) {
                    $gafacompany->setDotValue('extra_fields.gympass.active', 1);
                } else {
                    $gafacompany->setDotValue('extra_fields.gympass.active', 0);
                }
            });
            $response['gympass'] = $gympass;
        }

        if (!$allowed) {
            unset($response['mail_from']);
            unset($response['name_from']);
        }

        return $response;
    }


    /**
     * Ultima funcion de salvado
     */
    public function runLastSave()
    {
        $gafacompany = $this;
        $request = \request();

        // ----*** Sync Company Webhooks ***----
        $webhooks = $request->get('webhook', []);
        $gafacompany->webhooks()->delete();
        if (count($webhooks) > 0) {
            foreach ($webhooks as $webhook_value) {
                if (!empty($webhook_value)) {
                    $gafacompany->webhooks()->create(['webhook' => $webhook_value]);
                }
            }
        }
        // ----*** Sync Company Webhooks ***----
    }

    public function extenderValidacion(&$validator)
    {
        $request = request();

        $picture = $request->file('pic', null);
        if ($picture) {
            $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];
            $contentType = $picture->getClientMimeType();
            if (!in_array($contentType, $allowedMimeTypes)) {
                $validator->errors()->add('pic', __('validation.image', ['attribute' => __('gafacompany.CompanyPhoto')]));
            }
        }
    }

    static protected function filtrarQueries(&$query)
    {
        parent::filtrarQueries($query);
    }

    /**
     * Link del modelo
     *
     * @return string
     */
    public function link(): string
    {
        return route('admin.company.dashboard', [
            'company' => $this->slug,
        ]);
    }
}
