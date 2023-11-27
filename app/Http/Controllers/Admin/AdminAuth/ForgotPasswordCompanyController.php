<?php

namespace App\Http\Controllers\Admin\AdminAuth;

use App\Librerias\Helpers\LibRoute;
use App\Librerias\Vistas\VistasGafaFit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordCompanyController extends ForgotPasswordController
{
    public function showLinkRequestForm()
    {
        return VistasGafaFit::view('admin.auth.passwords.email', [
            'company' => LibRoute::getCompany(request()),
        ]);
    }

}
