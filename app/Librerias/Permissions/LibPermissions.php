<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 05/03/18
 * Time: 11:38
 */

namespace App\Librerias\Permissions;


use App\Admin;
use App\Interfaces\HasChildLevels;
use App\Interfaces\HasParentsLevels;
use Bouncer;
use Illuminate\Database\Eloquent\Model;

abstract class LibPermissions
{
    const ACCESS = 'access';
    const ALL = 'all';
    const LEVEL_GAFAFIT = 'gafafit';
    const LEVEL_COMPANY = 'company';
    const LEVEL_BRAND = 'brand';
    const LEVEL_LOCATION = 'location';

    /**
     * @param Admin $user
     * @param null  $relacion
     *
     * @return bool
     */
    static public function userCanAccessTo(Admin $user, $relacion = null)
    {
        return self::userCan($user, static::ACCESS, $relacion);
    }

    /**
     * User can
     *
     * @param Admin  $user
     * @param string $permission
     * @param null   $relacion
     *
     * @return bool
     */
    static public function userCan(Admin $user, string $permission, $relacion = null)
    {
        $respuesta = false;

        $roles = $user->roles;
        $all = static::ALL;

        $roles->each(function ($rol) use ($user, &$respuesta, $relacion, $permission, $all) {
            //Solo si no hubo respuesta
            if ($respuesta === false) {

                /**
                 * @var Role $rol
                 */
                if ($relacion === null) {
                    if ($rol->can($all) || $rol->can($permission)) {
                        $respuesta = true;
                    };
                } else {
                    if ($relacion instanceof Model) {
                        //Un Elemento determinado
                        if ($rol->can($all, $relacion) || $rol->can($permission, $relacion)) {
                            //Se comprobó que se puede interactuar, ahora se verá si la asignación
                            //permite la interaccion
                            if (
                                $rol->pivot->assigned_type === null
                                &&
                                $rol->pivot->assigned_id === null
                            ) {
                                //El usuario accede porque tiene todos los permisos a nivel GafaFit
                                $respuesta = true;
                            } else if (
                                $rol->pivot->assigned_type === get_class($relacion)
                                &&
                                $rol->pivot->assigned_id == $relacion->id
                            ) {
                                //El usuario accede porque el rol se asigno al elemento en cuestion
                                $respuesta = true;
                            } else {
                                if ($relacion instanceof HasChildLevels) {
                                    //se debe de comprobar si los hijos tienen el permiso
                                    $relacion->childsLevels()->each(function ($child) use ($rol, &$respuesta) {
                                        if ($respuesta === false) {
                                            //Solo si no encontramos la respuesta
                                            if (
                                                $rol->pivot->assigned_type === get_class($child)
                                                &&
                                                $rol->pivot->assigned_id == $child->id
                                            ) {
                                                $respuesta = true;
                                            }
                                        }
                                    });
                                }
                                if (
                                    $respuesta === false
                                    &&
                                    $relacion instanceof HasParentsLevels
                                ) {
                                    //Si hasta este punto siguen habiendo errores vamos a comprobar si alguno
                                    //de los padres tienen este problema
                                    $relacion->parentsLevels()->each(function ($child) use ($rol, &$respuesta) {
                                        if ($respuesta === false) {
                                            //Solo si no encontramos la respuesta
                                            if (
                                                $rol->pivot->assigned_type === get_class($child)
                                                &&
                                                $rol->pivot->assigned_id == $child->id
                                            ) {
                                                $respuesta = true;
                                            }
                                        }
                                    });
                                }
                            }
                        };
                    } else {
                        dd('El sistema no tiene globales', __FILE__, __LINE__);
                    }
                }
            }
        });

        return $respuesta;
    }

    /**
     * @param Admin $user
     * @param null  $relacion
     *
     * @return bool
     */
    static public function userCannotAccessTo(Admin $user, $relacion = null)
    {

        return !(self::userCanAccessTo($user, $relacion));
    }

    /**
     * @param Admin  $user
     * @param string $permission
     * @param null   $relacion
     *
     * @return bool
     */
    static public function userCannot(Admin $user, string $permission, $relacion = null)
    {
        return !(self::userCan($user, $permission, $relacion));
    }
}
