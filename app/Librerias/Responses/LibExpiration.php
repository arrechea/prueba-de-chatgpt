<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 20/02/2019
 * Time: 09:45
 */

namespace App\Librerias\Responses;


use App\Librerias\Catalog\LibCatalogoResponse;

class LibExpiration
{
    /**
     * Mapea los combos o membresías para cambiar los días de expiración a 30 si el campo es nulo
     *
     * @param LibCatalogoResponse $response
     *
     * @return LibCatalogoResponse
     */
    public static function mapNullExpiration(LibCatalogoResponse &$response)
    {
        $items = $response->getRespuestas();

        $items = $items->map(function ($item) {
            $item->expiration_days = $item->expiration_days ?? 30;

            return $item;
        });

        $response->getRespuestas()->setCollection($items);

        return $response;
    }
}
