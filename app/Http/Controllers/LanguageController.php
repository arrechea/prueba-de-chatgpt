<?php

namespace App\Http\Controllers;

use App\Librerias\Language\LibLanguage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LanguageController extends Controller
{
    /**
     * @param Request $request
     * @param         $language
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ChangeLanguage(Request $request, $language)
    {
        LibLanguage::changeLanguage($language);

        return response()->json(true);
    }

    /**
     *
     */
    public function jsFile()
    {
        $strings = Cache::rememberForever('lang.js', function () {
            $lang = config('app.locale');

            $files = glob(resource_path('lang/' . $lang . '/*.php'));
            $strings = [];

            foreach ($files as $file) {
                $name = basename($file, '.php');
                $strings[ $name ] = require $file;
            }

            return $strings;
        });

        header('Content-Type: text/javascript');
        echo('window.i18n = ' . json_encode($strings) . ';');
        exit();
    }
}
