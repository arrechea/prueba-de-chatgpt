<?php

namespace App\Http\Controllers\Admin\Company;


use App\Http\Controllers\AdminController;
use App\Librerias\Vistas\VistasGafaFit;
use Illuminate\Http\Request;

class ServiceController extends CompanyLevelController
{
    public function index()
    {
        return VistasGafaFit::view('admin.company.services.index') ;
    }
}
