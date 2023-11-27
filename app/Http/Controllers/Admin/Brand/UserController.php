<?php

namespace App\Http\Controllers\Admin\Brand;

use App\Http\Controllers\Admin\Company\CompanyController;
use App\Http\Controllers\Admin\Metric\MetricController;
use App\Http\Controllers\AdminController;
use App\Librerias\Vistas\VistasGafaFit;

class UserController extends AdminController
{
    public function index()
    {
        return VistasGafaFit::view('admin.brand.users.index') ;
    }
}
