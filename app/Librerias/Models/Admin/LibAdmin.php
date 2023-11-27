<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 27/03/18
 * Time: 12:23
 */

namespace App\Librerias\Models\Admin;


use App\Admin;
use App\AssignedRol;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Company\CatalogCompany;
use App\Librerias\Catalog\Tables\GafaFit\CatalogAdmin;
use App\Librerias\Catalog\Tables\GafaFit\CatalogAdminProfile;
use App\Librerias\Permissions\Role;
use App\Models\Admin\AdminProfile;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

abstract class LibAdmin
{
    /**
     * @param       $request
     * @param Admin $administrator
     *
     * @return Admin
     */
    static public function edit($request, Admin $administrator)
    {
        CatalogFacade::save($request, 'admins');

        $nuevoRequestParaPerfil = $request->all();
        unset($nuevoRequestParaPerfil['id']);
        $nuevoRequestParaPerfil['admins_id'] = $administrator->id;

        $request->replace($nuevoRequestParaPerfil);

        CatalogFacade::save($request, 'admin_profiles', $administrator->profile_catalog);

        return $administrator;
    }

    /**
     * @param $request
     *
     * @return \App\Librerias\Catalog\LibCatalogoModel
     */
    static public function create($request)
    {
        $nuevo = CatalogFacade::save($request, 'admins');

        $nuevoRequestParaPerfil = $request->all();
        unset($nuevoRequestParaPerfil['id']);
        $nuevoRequestParaPerfil['admins_id'] = $nuevo->id;

        $request->replace($nuevoRequestParaPerfil);

        CatalogFacade::save($request, 'admin_profiles', $nuevo->profile_catalog);

        return $nuevo;
    }

    /**
     * @param $administrator
     *
     * @return mixed
     */
    static public function getRoles($administrator)
    {
        $administrator->load([
            'roles',
            'roles.company',
            'roles.brand',
            'roles.location',
            'roles.owner',
        ]);
        $roles = $administrator->roles;
        $roles->map(function ($rol) {
            $pivot = $rol->pivot;
            switch ($pivot->assigned_type) {
                case Company::class:
                    $resultado = new Collection([Company::find($pivot->assigned_id)]);
                    break;
                case Brand::class:
                    $brand = Brand::withTrashed()->where('id', $pivot->assigned_id)->first();
                    $resultado = ($brand->parentsLevels())->concat([$brand]);
                    break;
                case Location::class:
                    $location = Location::withTrashed()->where('id', $pivot->assigned_id)->first();
                    $resultado = ($location->parentsLevels())->concat([$location]);
                    break;
                default:
                    $resultado = new Collection();
            }
            $rol->parentsLevels = $resultado;

            return $rol;
        });

        return $roles;
    }

    /**
     * Assign Roles.
     * Request with:
     * $_POST['rolgafafit'][$elementID] = $rolId
     * $_POST['rolcompanies'][$elementID] = $rolId
     * $_POST['rolbrands'][$elementID] = $rolId
     * $_POST['rollocations'][$elementID] = $rolId
     *
     * @param Request    $request
     * @param Admin      $admin
     * @param Model|null $asociado Delete all relationships down thisone
     */
    static public function assignRoles(Request $request, Admin $admin, Model $asociado = null)
    {
        $rolGafafit   = $request->rolgafafit ?? null;
        $rolCompanies = new Collection($request->rolcompanies ?? null);
        $rolBrands    = new Collection($request->rolbrands ?? null);
        $rolLocations = new Collection($request->rollocations ?? null);

        //Eliminamos asociaciones previas
        $queryDeEliminacion = AssignedRol::where('entity_id', $admin->id)->where('entity_type', Admin::class);

        if (is_null($asociado)) {
            //Si estamos en Gafafit eliminamos todas
        } else if (($asociado instanceof Company) || ($asociado instanceof CatalogCompany)) {
            /***************
             * Etapa eliminacion Company
             ***************/
            //Si estamos en una company eliminamos solo las de esa company para abajo
            $brandsCompany = $asociado->brands()->select('id')->get()->pluck('id');
            $locationsCompany = $asociado->locations()->select('id')->get()->pluck('id');

            //roles de esta company
            $queryDeEliminacion->where(function ($queryDeEliminacion) use ($asociado, $brandsCompany, $locationsCompany) {
                $queryDeEliminacion->where(function ($query) use ($asociado) {
                    $query->where(function ($query) use ($asociado) {
                        $query->where('assigned_id', $asociado->id)->where('assigned_type', Company::class);
                    });
                });
                //roles de los brands hijos
                $queryDeEliminacion->orWhere(function ($query) use ($asociado, $brandsCompany) {
                    $query->where(function ($query) use ($asociado, $brandsCompany) {
                        $query->whereIn('assigned_id', $brandsCompany->toArray())->where('assigned_type', Brand::class);
                    });
                });
                //roles de los locations hijos
                $queryDeEliminacion->orWhere(function ($query) use ($asociado, $locationsCompany) {
                    $query->where(function ($query) use ($asociado, $locationsCompany) {
                        $query->whereIn('assigned_id', $locationsCompany->toArray())->where('assigned_type', Location::class);
                    });
                });
            });
            /***************
             * Etapa filtrado Company
             ***************/
            //$rolGafafit = null;
            $rolCompanies = $rolCompanies->filter(function ($rol, $index) use ($asociado) {
                return $index == $asociado->id;
            });
            $rolBrands = $rolBrands->filter(function ($rol, $index) use ($brandsCompany) {
                return in_array($index, $brandsCompany->toArray());
            });
            $rolLocations = $rolLocations->filter(function ($rol, $index) use ($locationsCompany) {
                return in_array($index, $locationsCompany->toArray());
            });

        } else if ($asociado instanceof Brand) {
            //Si estamos en una brand eliminamos solo las de ese brand para abajo
            dd('No se hizo. LibAdmin::assignRoles para brand');
        } else if ($asociado instanceof Location) {
            //Si estamos en una location eliminamos solo las de esa location
            dd('No se hizo. LibAdmin::assignRoles para location');
        }
        $queryDeEliminacion->delete();

        /*
         * Roles GafaFit
         */
        if ($rolGafafit) {
            AssignedRol::create([
                'role_id'     => $rolGafafit,
                'entity_id'   => $admin->id,
                'entity_type' => Admin::class,
            ]);
        }
        /*
         * RolesCompany
         */
        if ($rolCompanies->count() > 0) {
            $rolCompanies->each(function ($rol, $indice) use ($admin) {
                if ($rol) {
                    AssignedRol::create([
                        'role_id'       => $rol,
                        'entity_id'     => $admin->id,
                        'entity_type'   => Admin::class,
                        'assigned_type' => Company::class,
                        'assigned_id'   => $indice,
                    ]);
                }
            });
        }
        /*
         * Roles Brands
         */
        if ($rolBrands->count() > 0) {
            $rolBrands->each(function ($rol, $indice) use ($admin) {
                if ($rol) {
                    AssignedRol::create([
                        'role_id'       => $rol,
                        'entity_id'     => $admin->id,
                        'entity_type'   => Admin::class,
                        'assigned_type' => Brand::class,
                        'assigned_id'   => $indice,
                    ]);
                }
            });
        }
        /*
         * Roles Locations
         */
        if ($rolLocations->count() > 0) {
            $rolLocations->each(function ($rol, $indice) use ($admin) {
                if ($rol) {
                    AssignedRol::create([
                        'role_id'       => $rol,
                        'entity_id'     => $admin->id,
                        'entity_type'   => Admin::class,
                        'assigned_type' => Location::class,
                        'assigned_id'   => $indice,
                    ]);
                }
            });
        }
    }

    /**
     * Regresa el perfil de administrador adecuado dependiendo del email y el companies_id dados.
     * Primero verifica si existe un perfil con ese email y si lo encuentra regresa éste.
     * Si no lo encuantra, busca un administrador con ese email y si lo encuentra, crea un perfil
     * asociado vacío. Si el email no se encuentra ni en perfiles ni en administradores, crea un
     * nuevo administrador con valores default: name= email dado, password='password' y
     * status='active'. Después crea el perfil vacío.
     * Después crea un nuevo rol asociado para ese administrador. Por default usa el rol
     * 'generic' y lo asocia con la compañía y el administrador. Finalmente regresa el perfil creado.
     *
     * @param Request $request
     * @param string  $admin_model
     * @param string  $profile_model
     *
     * @return \App\Librerias\Catalog\LibCatalogoModel
     * @throws \Illuminate\Validation\ValidationException
     */
    static public function createProfileByEmailAndCompany(Request $request, $admin_model = CatalogAdmin::class, $profile_model = CatalogAdminProfile::class)
    {
        $companies_id = $request->get('companies_id', null);

        $email = $request->get('email');

        $profile = AdminProfile::where([
            ['email', $email],
            ['companies_id', $companies_id],
        ])->first();

        if ($profile !== null) {
            return $profile;
        } else {
            $admin = Admin::where('email', $email)->first();
            $new = true;

            if ($admin === null) {
                if (($request->get('password', null)) === null)
                    $request->merge(['password' => 'password']);
                $request->merge(['name' => $email]);
                $request->merge(['status' => 'on']);
                $admin = CatalogFacade::save($request, $admin_model);
            } else {
                $new = false;
            }

            $admin_profile = $admin->profile()->where('companies_id', $companies_id)->first();
            if ($admin_profile !== null) {
                return $admin_profile;
            }

            $new_req = $request->all();
            if (($request->get('password', null)) === null)
                $new_req['password'] = str_random(20);
            $new_req['status'] = 'on';
            $new_req['admins_id'] = $admin->id;
            $new_req['companies_id'] = (int)$companies_id;
            if (!$new) {
                $new_req['first_name'] = $admin->name;
//                $new_req['password'] = $admin->password;
            }
            unset($new_req['id']);
            $request->replace($new_req);
            $newProfile = CatalogFacade::save($request, $profile_model);

//            if ($companies_id && $newProfile) {
//                $role = Role::where('name', 'generic')->first();
//
//                if ($role !== null) {
//                    AssignedRol::updateOrCreate([
//                        'role_id'       => $role->id,
//                        'entity_type'   => Admin::class,
//                        'entity_id'     => $admin->id,
//                        'assigned_type' => Company::class,
//                        'assigned_id'   => $companies_id,
//                    ]);
//                }
//            }

            return $newProfile;
        }
    }

    /**
     * Obtiene el id de todos los administradores asociados con la compañía (estos son
     * los que tengan al menos un rol asociado a la compáñía, una de sus marcas o
     * una de sus ubicaciones). Después comprueba que la compañía actual concuerde con
     * la compañía del perfil.
     *
     * @param Company      $company
     * @param AdminProfile $profile
     *
     * @return bool
     */
    public static function confirmarAdmins(Company $company, AdminProfile $profile)
    {
        $admins = $company->admins()->pluck('id')->toArray();
        $id = $profile->admins_id;

        return in_array($id, $admins) && $company->id === $profile->companies_id;
    }
}
