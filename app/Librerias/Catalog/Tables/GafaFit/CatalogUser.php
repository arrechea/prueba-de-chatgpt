<?php

namespace App\Librerias\Catalog\Tables\GafaFit;


use App\Librerias\Catalog\LibCatalogoModel;
use App\Librerias\Catalog\LibDatatable;
use App\Librerias\Catalog\LibValoresCatalogo;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\User\UserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CatalogUser extends LibCatalogoModel
{
    use UserTrait, SoftDeletes;
    protected $table = 'users';

    /**
     * @return string
     */
    public function GetName()
    {
        return 'Users';
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
        $user = $this;
        $botones = new LibValoresCatalogo($this, __('users.Actions'), '', [
            'validator'    => '',
            'notOrdenable' => true,
        ]);
        $botones->setGetValueNameFilter(function ($lib, $value) use ($user) {
            $micro = LibDatatable::GetTableId();

            $botonEdit = LibPermissions::userCan(Auth::user(), LibListPermissions::USER_EDIT) ?
                '<a class="btn btn-floating waves-effect waves-light" href="' . route('admin.users.edit', ['user' => $user->id]) . '"><i class="material-icons ">mode_edit</i></a>'
                :
                '';

            return "$botonEdit";
        });

//

        return [
            (new LibValoresCatalogo($this, __('users.Active'), '', [
                'validator' => '',
            ]))->setGetValueNameFilter(function ($lib, $value) use ($user) {
                return VistasGafaFit::view('admin.common.check', [
                    'active' => $user->isActive(),
                ])->render();
            }),
            new LibValoresCatalogo($this, __('users.Name'), 'name', [
                'validator' => 'required|string|max:100',
            ], function () use ($user, $request) {
                //Extras
                if (!$user->api_token) {
                    $user->api_token = str_random(60);
                }

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
            new LibValoresCatalogo($this, __('users.Email'), 'email', [
                'validator' => [
                    'required',
                    'string',
                    'max:100',
                    'email',
                    Rule::unique($this->GetTable(), 'email')
                        ->ignore($this->id, 'id'),
                ],
            ]),
            new LibValoresCatalogo($this, __('users.Password'), 'password', [
                'validator'    => 'max:100',
                'type'         => 'password',
                'hiddenInList' => true,
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
        $password = $request->get('password', '');
        $password_confirmation = $request->get('password_confirmation', '');
        if ($password !== '') {
            if ($password !== $password_confirmation) {
                $validator->errors()->add('password', __('validation.confirmed', ['attribute' => __('users.Password')]));
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
        return route('admin.users.edit', [
            'user' => $this,
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
