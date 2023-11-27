<?php
/**
 * Created by IntelliJ IDEA.
 * User: wisquimas
 * Date: 29/05/18
 * Time: 11:40
 */

namespace App\Librerias\Language;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;


abstract class LibLanguage
{
    /**
     * @param string $language
     */
    static public function changeLanguage(string $language)
    {
        $rules = [
            'language' => 'en,es' // -- list of supported languages of your application.
        ];

        $validator = Validator::make(compact($language), $rules);

        if ($validator->passes()) {
            Session::put('language', $language);
        }
    }
}
