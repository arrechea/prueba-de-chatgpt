<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class GafaFitRequest
 *
 * @package App\Http\Requests
 */
abstract class GafaFitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    final public function authorize()
    {
        //Aca van las autorizaciones globales

        return $this->authorizeEspecifico();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    final public function rules()
    {
        // aca van las reglas globales
        $reglasGlobales = [];
        $reglasEspecifias = $this->rulesEspecifico();

        return array_merge($reglasGlobales, $reglasEspecifias);
    }

    /**
     * Autorizacion especifica para continuar
     *
     * @return bool
     */
    abstract public function authorizeEspecifico(): bool;

    /**
     * Reglas especificas para continuar
     *
     * @return array
     */
    abstract public function rulesEspecifico(): array;
}
