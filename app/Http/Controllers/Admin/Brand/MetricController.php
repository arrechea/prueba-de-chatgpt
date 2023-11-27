<?php

namespace App\Http\Controllers\Admin\Brand;

use App\Http\Controllers\AdminController;
use App\Librerias\Vistas\VistasGafaFit;
use Illuminate\Http\Request;

class MetricController extends AdminController
{
    public function index()
    {
        return VistasGafaFit::view('admin.brand.metrics.index') ;
    }
}
