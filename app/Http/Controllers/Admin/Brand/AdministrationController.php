<?php

namespace App\Http\Controllers\Admin\Brand;

use App\Http\Controllers\AdminController;
use App\Librerias\Vistas\VistasGafaFit;
use Illuminate\Http\Request;

class AdministrationController extends AdminController
{
    public function index()
    {
        return VistasGafaFit::view('admin.brand.administration.index') ;
    }
}
