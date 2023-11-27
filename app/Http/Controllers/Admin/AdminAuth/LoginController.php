<?php

namespace App\Http\Controllers\Admin\AdminAuth;

use App\Admin;
use App\Http\Controllers\AdminController;
use App\Librerias\Vistas\VistasGafaFit;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Hesto\MultiAuth\Traits\LogsoutGuard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends AdminController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers, LogsoutGuard {
        LogsoutGuard::logout insteadof AuthenticatesUsers;
    }

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    public $redirectTo = '/admin/home';

    /**
     * Get the path that we should redirect once logged out.
     * Adaptable to user needs.
     *
     * @return string
     */
    public function logoutToPath()
    {
        return route('admin.init');
    }

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('admin.guest', ['except' => 'logout']);
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm(Request $request)
    {
        if ($hasToken = ($request->token ? true : false)) {
            try {
                $token = (array) JWT::decode($request->token, config('app.jwt_secret'), ['HS256']);

                $email = $token['sub'];
                $password = $token['password'];
            } catch (\Exception $e) {
                // TODO: redirect to login page with an error message
                return response()->json($e->getMessage());
            }
        }

        return VistasGafaFit::view('admin.auth.login', [
            'hasToken' => $hasToken,
            'email'    => $email ?? null,
            'password' => $password ?? null,
        ]);
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * Check admin user credential with active state (Overriding credential function)
     *
     * @param  Request $request [description]
     *
     * @return array
     */
    protected function credentials(Request $request)
    {
        return array_merge(
            $request->only($this->username(), 'password'),
            ['status' => Admin::STATE_ACTIVE]
        );
    }
}
