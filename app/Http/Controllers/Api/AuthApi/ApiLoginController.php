<?php

namespace App\Http\Controllers\Api\AuthApi;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;

class ApiLoginController extends Controller
{
    use AuthenticatesUsers;

    protected function authenticated(Request $request, $user)
    {
        $request->request->add([
            'username' => $user->email,
            'scope' => $user->role,
        ]);

        $tokenRequest = Request::create(
            '/oauth/token',
            'post',
            $request->all()
        );

        $tokenRequest->headers->set('Content-Type', 'application/json');
        $tokenRequest->headers->set('GAFAFIT-COMPANY', $request->headers->all()['gafafit-company']);

        return Route::dispatch($tokenRequest);
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        return $this->authenticated($request, $this->guard()->user())
            ?: response()->json(["status" => "error", "message" => "Error intentando realizar la autenticacion"]);

    }

    /**
     * Get the failed login response instance.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        return response()->json([
            "status"  => "error",
            "message" => "Autentication Error",
            "data"    => [
                "errors" => [
                    $this->username() => Lang::get('auth.failed'),
                ],
            ],
        ]);
    }
}
