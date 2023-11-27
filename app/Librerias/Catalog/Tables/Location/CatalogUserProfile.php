<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 06/04/2018
 * Time: 11:54 AM
 */

namespace App\Librerias\Catalog\Tables\Location;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Catalogs\HasSpecialTexts;
use App\Models\Company\Company;
use App\Models\User\ProfileTrait;
use App\Traits\TraitConImagen;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class CatalogUserProfile extends LibCatalogoModel implements HasSpecialTexts
{
    use TraitConImagen, ProfileTrait, SoftDeletes;
    protected $table = 'user_profiles';
    protected $casts = [
        'verified' => 'boolean',
        'blocked_reserve' => 'boolean'
    ];
    protected $hidden = [
        'blocked_reserve',
    ];


    /**
     * @return string
     */
    public function GetName()
    {
        return 'User Profile';
    }


    /**
     * Filtrado de los usuarios basado en la compañía del perfil
     *
     * @param $query
     */
    static protected function filtrarQueries(&$query)
    {
        $comp_id = LibFilters::getFilterValue('comp_id');
        if ($comp_id) {
            $query->where('companies_id', $comp_id);
        }
    }

    /**
     * Obtiene los valores que se van a guardar y los que se van a
     * desplegar en el listado.
     * Están filtrados por compañía.
     *
     * @param Request|null $request
     *
     * @return LibValoresCatalogo[]|array
     */
    public function Valores(Request $request = null)
    {
        $profile = $this;

        $request = $request ? $request : \request();
        $comp_id = $request->get('companies_id');

        return [
            (new LibValoresCatalogo($this, __('users.Active'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function ($lib, $value) use ($profile) {
                return VistasGafaFit::view('admin.common.check', [
                    'active' => $profile->isActive(),
                ])->render();
            }),
            (new LibValoresCatalogo($this, __('users.Verified'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function ($lib, $value) use ($profile) {
                return VistasGafaFit::view('admin.common.check', [
                    'active' => $profile->isVerified(),
                ])->render();
            }),
            (new LibValoresCatalogo($this, __('users.Name'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function () use ($profile) {
                return $profile->first_name . ' ' . $profile->last_name;
            }),
            new LibValoresCatalogo($this, __('users.Email'), 'email', [
                'validator' => [
                    'required',
                    'string',
                    'max:100',
                    'email',
                    Rule::unique($this->GetTable(), 'email')
                        ->ignore($this->id, 'id')
                        ->where('companies_id', $profile->companies_id)
                        ->whereNull('deleted_at'),
                ],
            ]),
//            new LibValoresCatalogo($this, '', 'users_id', [
//                'validator'    => [
//                    'required',
//                    'integer',
//                    'min:0',
//                    'exists:users,id',
//                    Rule::unique($this->GetTable(), 'users_id')
//                        ->ignore($this->id, 'id')
//                        ->where('companies_id', $comp_id),
//                ],
//                'hiddenInList' => true,
//            ]),
            new LibValoresCatalogo($this, __('users.FirstName'), 'first_name', [
                'validator' => 'max:190|string',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, __('users.Lastname'), 'last_name', [
                'validator' => 'max:190|string',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'password', [
                'validator' => 'max:100',
                'type' => 'password',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'birth_date', [
                'validator' => 'nullable|date',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'address', [
                'validator' => 'max:190|string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'internal_number', [
                'validator' => 'max:190|string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'external_number', [
                'validator' => 'max:190|string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'municipality', [
                'validator' => 'max:190|string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'postal_code', [
                'validator' => 'max:190|string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'city', [
                'validator' => 'string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'countries_id', [
                'validator' => 'exists:countries,id|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'country_states_id', [
                'validator' => 'exists:country_states,id|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'phone', [
                'validator' => 'max:190|string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'cel_phone', [
                'validator' => 'max:190|string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'gender', [
                'validator' => 'in:male,female|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'picture', [
                'validator' => '',
                'hiddenInList' => true,
                'type' => 'file',
            ]),
//            new LibValoresCatalogo($this, '', 'shoe_size', [
//                'validator'    => 'string|nullable',
//                'hiddenInList' => true,
//            ]),
            (new LibValoresCatalogo($this, __('users.Actions'), '', [
                'validator' => '',
                'notOrdenable' => true,
            ]))->setGetValueNameFilter(function () use ($profile) {
                return VistasGafaFit::view('admin.location.users.buttons', [
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
        return VistasGafaFit::view('admin.company.companies.info');
    }

    /**
     * Link del modelo
     *
     * @return string
     */
    public function link(): string
    {
        return route('admin.company.users.edit', [
            'user' => $this,
        ]);
    }

    protected function extenderValidacion(&$validator)
    {
        $request = request();

        $picture = $request->file('picture', null);
        if ($picture) {
            $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];
            $contentType = $picture->getClientMimeType();
            if (!in_array($contentType, $allowedMimeTypes)) {
                $validator->errors()->add('pic', __('validation.image', ['attribute' => __('users.ProfilePicture')]));
            }
        }
    }

    protected static function ColumnToSearch()
    {
        $companies_id = LibFilters::getFilterValue('comp_id', \request());

        return function ($query, $criterio) use ($companies_id) {
            $query->where(function ($q) use ($criterio) {
                $q->where('first_name', 'like', "%{$criterio}%");
                $q->orWhere('last_name', 'like', "%{$criterio}%");
                $q->orWhere('email', 'like', "%{$criterio}%");
            });

            $query->where('companies_id', $companies_id);
        };
    }
}
