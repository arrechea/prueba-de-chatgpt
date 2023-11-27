<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 18/07/2018
 * Time: 11:59 AM
 */

namespace App\Librerias\Client;


use App\Models\Company\Company;
use Illuminate\Support\Facades\DB;

class LibClient
{
    /**
     * Recibe la compañía y comprueba que tenga un cliente asociado. Después crea una clave
     * random de 40 caracteres y lo guarda en la tabla oauth_clients y regresa el string
     *
     * @param Company $company
     *
     * @return array
     */
    public static function generateClientSecret(Company $company)
    {
        /*
         * Crea un string aleatorio de 40 caracteres y busca que no exista dentro de la
         * tabla. Esto es en caso de que se genere una clave igual a una ya existente (muy poco probable).
         * Si luego de 100 intentos no se puede generar una clave única, se manda un error.
         */
        $i = 1;
        do {
            $secret = str_random(40);
            $failed = DB::table('oauth_clients')->where('secret', $secret)->exists();
            $i++;
        } while ($failed && $i < 100);

        //Si falló en crear una clave única envía un error
        if ($failed)
            abort(500);

        //Asigna la nueva clave al cliente y lo guarda
        $saved = DB::table('oauth_clients')->updateOrInsert([
            'name'         => $company->slug,
            'companies_id' => $company->id,
        ], [
            'secret'                 => $secret,
            'redirect'               => '',
            'personal_access_client' => false,
            'password_client'        => true,
            'revoked'                => false,
        ]);

        if ($saved) {
            $client = DB::table('oauth_clients')->where([
                ['name', $company->slug],
                ['companies_id', $company->id],
            ])->first();

            return [
                'id'     => $client->id,
                'secret' => $client->secret,
            ];
        }

        return null;
    }
}
