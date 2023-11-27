<?php

namespace App\Http\Controllers\Admin\AdminAuth;

use App\Http\Controllers\AdminController;
use App\Librerias\Helpers\LibRoute;
use App\Librerias\Vistas\VistasGafaFit;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Str;

class ResetPasswordCompanyController extends ResetPasswordController
{
    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string|null              $token
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showResetForm(Request $request, $token = null)
    {
        return VistasGafaFit::view('admin.auth.passwords.reset')->with([
            'token'   => $request->route('token'),
            'email'   => $request->email,
            'company' => LibRoute::getCompany($request),
        ]);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword $user
     * @param  string                                      $password
     *
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $profileUser = $user->getProfileInThisCompany();
        if ($profileUser) {
            $profileUser->password = $password;

            $user->setRememberToken(Str::random(60));

            $profileUser->save();
            $user->save();

            event(new PasswordReset($user));

            $this->guard()->login($user);
        }
    }
}
