<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Librerias\Language\LibLanguage;
use Illuminate\Http\Request;

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
}
