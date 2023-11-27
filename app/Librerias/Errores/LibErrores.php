<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 12/03/2019
 * Time: 12:46
 */

namespace App\Librerias\Errores;

use Illuminate\Validation\ValidationException;

class LibErrores
{
    /**
     * @param array|string $errores
     *
     * @throws ValidationException
     */
    static public function lanzarErrores($errores)
    {
        if (!is_array($errores)) {
            if (is_string($errores)) {
                $errores = [$errores];
            } else {
                $errores = ['6969: $errores debe de ser un array|string'];
            }
        }
        $validator = self::generarValidadorConErrores($errores);

        throw new ValidationException($validator);
    }

    /**
     * @param array|string $mensajes
     * @param string       $tipo
     */
    static public function lanzarMensajes($mensajes, $tipo = 'success')
    {
        if (!is_array($mensajes)) {
            if (is_string($mensajes)) {
                $mensajes = [$mensajes];
            } else {
                $mensajes = ['6969: $mensajes debe de ser un array|string'];
            }
        }

        $request = request();
        if ($mensajes) {
            $nuevoMensaje = [];
            $anteriores = $request->session()->get("alert-{$tipo}");
            if ($anteriores) {
                $nuevoMensaje[] = $anteriores;
            }
            foreach ($mensajes as $mensaje) {
                $nuevoMensaje[] = $mensaje;
            }

            $nuevoMensaje = implode('<br/>', $nuevoMensaje);
            $request->session()->flash("alert-{$tipo}", $nuevoMensaje);
        }
    }

    /**
     * @param array $errores
     *
     * @return \Illuminate\Validation\Validator
     */
    static public function generarValidadorConErrores(array $errores = [])
    {
        $validator = \Validator::make([], []);
        foreach ($errores as $error) {
            $validator->errors()->add('validation', $error);
        }

        return $validator;
    }
}
