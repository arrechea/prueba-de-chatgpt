<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 27/03/18
 * Time: 12:14
 */

namespace App\Librerias\Catalog\Tables\GafaFit;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibDatatable;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Admin\AdminProfileTrait;
use App\Models\Admin\AdminTrait;
use App\Models\Company\Company;
use App\Traits\TraitConImagen;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CatalogAdminProfile extends LibCatalogoModel
{
    use AdminProfileTrait, TraitConImagen, SoftDeletes;
    protected $table = 'admin_profiles';

    /**
     * @return string
     */
    public function GetName()
    {
        return 'Admin Profile';
    }

    /**
     * @param Request|null $request
     *
     * @return LibValoresCatalogo[]
     */
    public function Valores(Request $request = null)
    {
        $profile = $this;
        $companies_id = null;
        $admins_id = 0;

        $companies_id = LibFilters::getFilterValue('id');

        if (\request()->has('companies_id')) {
            $companies_id = \request()->get('companies_id');
        }
        if (\request()->has('admins_id')) {
            $admins_id = \request()->get('admins_id', 0);
        }

        $age = new LibValoresCatalogo($this, __('administrators.Age'), '', [
            'validator' => '',
        ]);
        $age->setGetValueNameFilter(function () use ($profile) {
            if ($profile) {
                if (!$profile->birth_date) {
                    return '--';
                } else {
                    $birth_date = Carbon::parse($profile->birth_date);

                    return $birth_date->diffInYears(Carbon::now());
                }
            }

            return null;
        });

        //Seteo de botones nivel gafafit
        $botones = new LibValoresCatalogo($this, __('gafacompany.Edit'), '', [
            'validator'    => '',
            'notOrdenable' => true,
        ]);
        $botones->setGetValueNameFilter(function ($lib, $value) use ($profile) {
            $micro = LibDatatable::GetTableId();

            $botonEdit = LibPermissions::userCan(Auth::user(), LibListPermissions::ADMIN_EDIT) ?
                '<a class="btn btn-floating waves-effect waves-light" href="' . route('admin.administrator.edit', ['user' => $profile->id]) . '"><i class="material-icons ">mode_edit</i></a>'
                :
                '';
            $botonDelete = LibPermissions::userCan(Auth::user(), LibListPermissions::ADMIN_DELETE) ?
                '
                <a class="waves-effect waves-light btn btn-floating" href="#eliminarA' . $micro . '" ><i class="material-icons">delete</i></a>

                <div id="eliminarA' . $micro . '" class="modal modal - fixed - footer modaldelete" data-method="get" data-href="' . route('admin.administrator.delete', [
                    'user' => $profile->id,
                ]) . '">
                    <div class="modal-content"></div>
                </div>
                ' : '';

            return "$botonEdit $botonDelete";
        });

        $name = new LibValoresCatalogo($this, __('administrators.Name'), 'full_name', [
            'validator'    => '',
            'notOrdenable' => false,
        ], function () use ($profile, $request) {
            if (
                $request->has('status')
                &&
                $request->get('status', '') === 'on'
            ) {
                $profile->status = 'active';
            } else {
                $profile->status = 'inactive';
            }
        });

//        $name->setGetValueNameFilter(function ($lib, $val) use ($profile) {
//            return $profile->first_name . ' ' . $profile->last_name;
//        });

        $age = new LibValoresCatalogo($this, __('administrators.Age'), '', [
            'validator' => '',
        ]);
        $age->setGetValueNameFilter(function () use ($profile) {
            if ($profile) {
                if (!$profile->birth_date) {
                    return '--';
                } else {
                    $birth_date = Carbon::parse($profile->birth_date);

                    return $birth_date->diffInYears(Carbon::now());
                }
            }

            return null;
        });

        $validator = [];

        if ($companies_id === null) {
            $validator = [
                'required',
                'string',
                'max:100',
                'email',
                Rule::unique($profile->GetTable(), 'email')
                    ->ignore($profile->id, 'id')
                    ->whereNull('companies_id')
                    ->whereNull('deleted_at')
                    ->where('admins_id', $admins_id),
            ];
        } else {
            $validator = [
                'required',
                'string',
                'max:100',
                'email',
                Rule::unique($profile->GetTable(), 'email')
                    ->ignore($profile->id, 'id')
                    ->where('companies_id', (int)$companies_id)
                    ->whereNull('deleted_at')
                    ->where('admins_id', $admins_id),
            ];
        }

        return [
            (new LibValoresCatalogo($this, __('users.Active'), '', [
                'validator'      => '',
            ]))->setGetValueNameFilter(function ($lib, $value) use ($profile) {
                return VistasGafaFit::view('admin.common.check', [
                    'active' => $profile->isActive(),
                ])->render();
            }),
            $name,
            new LibValoresCatalogo($this, __('administrators.E-mail'), 'email', [
                'validator' => $validator,
            ]),
            (new LibValoresCatalogo($this, __('administrators.Roles'), '', [
                'validator'    => '',
                'hiddenInList' => true,
            ])),
            new LibValoresCatalogo($this, '', 'admins_id', [
                'validator'    => [
                    'required',
                    'integer',
//                    Rule::unique($profile->GetTable(), 'admins_id')
//                        ->ignore($profile->id, 'id')
//                        ->where('companies_id', (int)$companies_id),
                ],
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'first_name', [
                'validator'    => 'max:190|string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'last_name', [
                'validator'    => 'max:190|string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'birth_date', [
                'validator'    => 'nullable|date',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'birth_date', [
                'validator'    => 'nullable|date',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'address', [
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
            new LibValoresCatalogo($this, __('administrators.Phone'), 'phone', [
                'validator'    => 'max:190|string|nullable',
                'hiddenInList' => false,
            ]),
            new LibValoresCatalogo($this, '', 'cel_phone', [
                'validator'    => 'max:190|string|nullable',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'gender', [
                'validator'    => 'nullable|in:male,female',
                'hiddenInList' => true,
            ]),
            new LibValoresCatalogo($this, '', 'password', [
                'validator'    => 'max:190|string|nullable',
                'type'         => 'password',
                'hiddenInList' => true,
            ]),
            $age,
            new LibValoresCatalogo($this, __('administrators.Profile'), 'pic', [
                'validator'    => '',
                'type'         => 'file',
                'notOrdenable' => true,
            ]),
            $botones,
        ];
    }


    /**
     * @param $validator
     */
    public function extenderValidacion(&$validator)
    {
        $request = request();
        /*
         * Validar password
         */
        $password = $request->get('password', '');
        $password_confirmation = $request->get('password_confirmation', '');
        if ($password !== '') {
            if ($password !== $password_confirmation) {
                $validator->errors()->add('password', __('validation.confirmed', ['attribute' => __('users.Password')]));
            }
        }
        /*
         * Validar imagen
         */
        $picture = $request->file('pic', null);
        if ($picture) {
            $allowedMimeTypes = ['image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/svg+xml'];
            $contentType = $picture->getClientMimeType();
            if (!in_array($contentType, $allowedMimeTypes)) {
                $validator->errors()->add('pic', __('validation.image', ['attribute' => __('users.ProfilePicture')]));
            }
        }
    }

    /**
     * Link del modelo
     *
     * @return string
     */
    public function link(): string
    {
        return route('admin.administrator.edit', [
            'administrator' => $this->admin,
        ]);
    }

    protected static function ColumnToSearch()
    {
        return function ($query, $criterio) {
            $query->where('first_name', 'like', "%{$criterio}%");
            $query->orWhere('last_name', 'like', "%{$criterio}%");
            $query->orWhere('email', 'like', "%{$criterio}%");
        };
    }
}
