<?php

namespace App\Librerias\Catalog\Tables\GafaFit;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibDatatable;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Helpers\LibFilters;
use App\Librerias\Permissions\HasRolesAndAbilities;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Admin\AdminTrait;
use App\Traits\TraitConImagen;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CatalogAdmin extends LibCatalogoModel
{
    use Notifiable, HasRolesAndAbilities, TraitConImagen, AdminTrait, SoftDeletes;
    protected $table = 'admins';

    /**
     * @return string
     */
    public function GetName()
    {
        return 'Admins';
    }

    /**
     * Devuelve los valores a procesar
     * Esto nos va a servir para declarar todas las valicaciones, etiquetas y opciones de salvado
     *
     * @param Request|null $request
     *
     * @return LibValoresCatalogo[]|array
     */
    public function Valores(Request $request = null)
    {
        $user = $this;
        $botones = new LibValoresCatalogo($this, __('gafacompany.Edit'), '', [
            'validator'    => '',
            'notOrdenable' => true,
        ]);

        $botones->setGetValueNameFilter(function ($lib, $value) use ($user) {
            $micro = LibDatatable::GetTableId();

            $botonEdit = LibPermissions::userCan(Auth::user(), LibListPermissions::ADMIN_EDIT) ?
                '<a class="btn btn-floating waves-effect waves-light" href="' . route('admin.administrator.edit', ['user' => $user->id]) . '"><i class="material-icons ">mode_edit</i></a>'
                :
                '';
//            $botonDelete = LibPermissions::userCan(Auth::user(), LibListPermissions::ADMIN_DELETE) ?
//                '
//                <a class="waves-effect waves-light btn btn-floating" href="#eliminarA' . $micro . '" ><i class="material-icons">delete</i></a>
//
//                <div id="eliminarA' . $micro . '" class="modal modal - fixed - footer modaldelete" data-method="get" data-href="' . route('admin.administrator.delete', [
//                    'user' => $user->id,
//                ]) . '">
//                    <div class="modal-content"></div>
//                </div>
//                ' : '';

            return "$botonEdit ";
        });


        return [
            (new LibValoresCatalogo($this, __('users.Active'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function ($lib, $value) use ($user) {
                return VistasGafaFit::view('admin.common.check', [
                    'active' => $user->isActive(),
                ])->render();
            }),
            new LibValoresCatalogo($this, __('administrators.Name'), 'name', [
                'validator' => '',
            ], function () use ($user, $request) {
                //Name
                $user->name = "{$request->get('first_name','')} {$request->get('last_name','')}";
                //Status
                if (
                    $request->has('status')
                    &&
                    $request->get('status', '') === 'on'
                ) {
                    $user->status = 'active';
                } else {
                    $user->status = 'inactive';
                }
            }),
            new LibValoresCatalogo($this, __('administrators.E-mail'), 'email', [
                'validator' => [
                    'required',
                    'string',
                    'max:100',
                    'email',
                    Rule::unique($this->GetTable(), 'email')
                        ->ignore($this->id, 'id'),
                ],
            ]),
            (new LibValoresCatalogo($this, __('administrators.Roles'), '', [
                'validator'    => '',
                'hiddenInList' => false,
            ]))->setGetValueNameFilter(function ($lib) use ($user) {
                $roles = $user->admin->roles->unique('id');

                return VistasGafaFit::view('admin.gafafit.administrators.roles', [
                    'roles' => $roles,
                ])->render();
            }),
            new LibValoresCatalogo($this, __('administrators.Password'), 'password', [
                'validator'    => 'max:100',
                'type'         => 'password',
                'hiddenInList' => true,
            ]),
//            $phone,
//            $age,
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

    static protected function filtrarQueries(&$query)
    {
        $query->with(['profile', 'admin.roles']);
    }

    /**
     * Link del modelo
     *
     * @return string
     */
    public function link(): string
    {
        return route('admin.administrator.edit', [
            'administrator' => $this,
        ]);
    }

    protected static function ColumnToSearch()
    {
        return function ($query, $criterio) {
            $query->where(function ($q) use ($criterio) {
                $q->where('name', 'like', "%{$criterio}%");
                $q->orWhere('email', 'like', "%{$criterio}%");
            });
        };
    }
}
