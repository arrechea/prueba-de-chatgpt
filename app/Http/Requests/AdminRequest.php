<?php

namespace App\Http\Requests;

/**
 * Class GafaFitRequest
 *
 * @package App\Http\Requests
 */
class AdminRequest extends GafaFitRequest
{
    /**
     * Autorizacion especifica para continuar
     *
     * @return bool
     */
    public function authorizeEspecifico(): bool
    {
        return true;
    }


    /**
     * Reglas especificas para continuar
     *
     * @return array
     */
    public
    function rulesEspecifico(): array
    {
        return [];
    }
}
