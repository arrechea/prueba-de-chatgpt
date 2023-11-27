<?php
/**
 * Created by IntelliJ IDEA.
 * User: ARGEL
 * Date: 06/04/2018
 * Time: 11:54 AM
 */

namespace App\Librerias\Catalog\Tables\Company;


use App\Admin;
use App\Events\UserProfile\ProfileUpdated;
use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibCatalogoRelationCustom;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Catalogs\HasSpecialTexts;
use App\Models\Company\Company;
use App\Models\User\ProfileTrait;
use App\Models\User\UserProfile;
use App\Notifications\User\NotificationWelcome;
use App\Traits\TraitConImagen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\SoftDeletes;

class CatalogUserProfile extends LibCatalogoModel implements HasSpecialTexts
{
    use TraitConImagen, ProfileTrait, SoftDeletes;
    protected $table = 'user_profiles';
    private $unVerified = false;
    protected $casts = [
        'verified' => 'boolean',
    ];

    private $previous_profile;


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
        $this->previous_profile = UserProfile::find($this->id);
        $request = \request();
        $comp_id = $request->get('companies_id');
        $company = null;

        if (\request()->has('companies_id')) {
            $companies_id = \request()->get('companies_id');
            $company = Company::find($companies_id);
        }

        $name = new LibValoresCatalogo($this, __('users.Name'), '', [
            'validator' => '',
        ]);
        $name->setGetValueNameFilter(function () use ($profile) {
            return $profile->first_name . ' ' . $profile->last_name;
        });

        $buttons = new LibValoresCatalogo($this, __('users.Actions'), '', [
            'validator'    => '',
            'notOrdenable' => true,
        ]);

        $buttons->setGetValueNameFilter(function ($lib) use ($company, $profile) {
            return VistasGafaFit::view('admin.company.users.buttons', [
                'profile' => $profile,
            ])->render();
        });

        //
        $categories = new LibValoresCatalogo(
            $this,
            __('users.UserCategories'),
            null,
            [
                'type'         => 'select',
                'validator'    => 'nullable|exists:user_categories,id',
                'hiddenInList' => true,
                'other'        => new LibCatalogoRelationCustom([]),
            ],
            static function () {
                $request = request();

                $validator = Validator::make($request->all(), ['categories' => 'array']);

                if ($validator->fails()) {
                    return back()->withErrors($validator->errors());
                }

                $selected = $request->get('categories', []);
                if ($id = $request->get('id')) {
                    UserProfile::find($id)->categories()->sync($selected);
                }
            }
        );

        $categories->setGetValueNameFilter(
            static function ($LibValoresCatalogo, $value) use ($profile) {
                $currentCategories = $profile->categories;

                $selected = null;
                if (count($currentCategories)) {
                    $selected =
                        $currentCategories
                            ->map(
                                static function ($category) {
                                    return $category->name;
                                }
                            )
                            ->implode(',');
                }

                return $selected;
            }
        );

        return [
            (new LibValoresCatalogo($this, __('users.Active'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function ($lib, $value) use ($profile) {
                return VistasGafaFit::view('admin.common.check', [
                    'active' => $profile->isActive(),
                ])->render();
            }),
            $name,
            new LibValoresCatalogo($this, __('users.Email'), 'email', [
                'validator' => [
                    'required',
                    'string',
                    'max:100',
                    'email',
                    Rule::unique($profile->GetTable(), 'email')
                        ->ignore($profile->id, 'id')
                        ->where('companies_id', $profile->companies_id)
                        ->whereNull('deleted_at'),
                ],
            ]),
            new LibValoresCatalogo($this, '', 'users_id', [
                'validator'    => [
                    'required',
                    'integer',
                    'min:0',
                    'exists:users,id',
                    Rule::unique($profile->GetTable(), 'users_id')
                        ->ignore($profile->id, 'id')
                        ->where('companies_id', $comp_id),
                ],
                'hiddenInList' => true,
            ], function () use ($profile, $request, $company) {
                //Extras
                if (
                    $request->has('status')
                    &&
                    $request->get('status', '') === 'on'
                ) {
                    $profile->status = 'active';
                } else {
                    $profile->status = 'inactive';
                }

                $admin = Auth::user();
                if ($admin instanceof Admin) {
                    if (LibPermissions::userCan($admin, LibListPermissions::USER_VERIFY, $company) && !!$company) {
                        if ($request->has('verified') && $request->get('verified') === 'on') {
                            $profile->verify();
                        } else {
                            $this->unVerified = $profile->isVerified();
                            $this->verified = false;
                            if (!$profile->token)
                                $profile->unVerify();
                        }
                    }
                }
            }),
            new LibValoresCatalogo($this, __('users.FirstName'), 'first_name', [
                'validator'    => 'max:190|string',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, __('users.Lastname'), 'last_name', [
                'validator'    => 'max:190|string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'password', [
                'validator'    => 'max:100',
                'type'         => 'password',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'birth_date', [
                'validator'    => 'nullable|date|date_format:Y-m-d',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'address', [
                'validator'    => 'max:190|string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'internal_number', [
                'validator'    => 'max:190|string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'external_number', [
                'validator'    => 'max:190|string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'municipality', [
                'validator'    => 'max:190|string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'postal_code', [
                'validator'    => 'max:190|string|nullable',
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
            new LibValoresCatalogo($this, '', 'phone', [
                'validator'    => 'max:190|string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'cel_phone', [
                'validator'    => 'max:190|string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'gender', [
                'validator'    => 'in:male,female|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'picture', [
                'validator'    => '',
                'hiddenInList' => true,
                'type'         => 'file',
            ]),
            new LibValoresCatalogo($this, '', 'token', [
                'validator'    => 'string|nullable',
                'hiddenInList' => true,
            ]),
//            new LibValoresCatalogo($this, '', 'shoe_size', [
//                'validator'    => 'string|nullable',
//                'hiddenInList' => true,
//            ]),
//            new LibValoresCatalogo($this, '', 'verified', [
//                'validator'    => 'boolean|nullable',
//                'hiddenInList' => true,
//            ]),
            $categories,
            $buttons,
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
     * @return string
     */
    static protected function ColumnToSearch()
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

    public function runLastSave()
    {
        if ($this->unVerified) {
            $this->user_profile->notify(new NotificationWelcome($this->user_profile, $this->token));
        }
        event(new ProfileUpdated($this->previous_profile, $this));
    }
}
