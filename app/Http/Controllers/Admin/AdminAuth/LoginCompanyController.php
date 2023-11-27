<?php

namespace App\Http\Controllers\Admin\AdminAuth;

use App\Admin;
use App\Http\Controllers\Admin\Brand\AdministrationController;
use App\Librerias\Helpers\LibRoute;
use App\Librerias\Vistas\VistasGafaFit;
use Illuminate\Http\Request;

class LoginCompanyController extends LoginController
{
    /**
     * Show the application's login form.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm(Request $request)
    {
        return VistasGafaFit::view('admin.auth.login', [
            'company' => LibRoute::getCompany(request()),
        ]);
    }

    /**
     *
     */
    public function redirectPath()
    {
        return route('admin.company.dashboard', [
            'company' => LibRoute::getCompany(request()),
        ]);
    }

    /**
     * @return string
     */
    public function logoutToPath()
    {
        return route('admin.companyLogin.init', [
            'company' => LibRoute::getCompany(request()),
        ]);
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect($this->logoutToPath());
    }
}
