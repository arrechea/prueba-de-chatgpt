<?php

namespace App\Http\Controllers\Api\AuthApi;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Librerias\Helpers\LibRoute;
use App\Models\User\UserProfile;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordController extends ApiController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
     */
    use ResetsPasswords;

    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest');
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        $userData = null;
        $passwordData = null;
        $profile = ForgotPasswordController::getUserProfile($request, $request->get('email'));

        $this->validate($request, $this->rules(), $this->validationErrorMessages());
        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $response = $this->broker()->reset($this->credentials($request), function ($user, $password) use (&$userData, &$passwordData, $profile) {
            $userData = $user;
            $passwordData = $password;
            $this->resetPassword($profile, $password);
        });

        if ($response == Password::PASSWORD_RESET) {

            return response()->json(trans('passwords.reset', [], $this->getLanguage()));
        } else if ($response === Password::INVALID_TOKEN) {
            return response()->json([
                'message' => __('messages.invalid-data', [], $this->getLanguage()),
                'errors'  => [
                    'token' => [
                        trans($response, [], $this->getLanguage()),
                    ],
                ],
            ], 422);
        } else {
            return response()->json([
                $request->input('email'),
                trans($response, [], $this->getLanguage()),
            ], 202);
        }
    }

    /**
     * Reset the given user's password.
     *
     * @param  UserProfile $profile
     * @param  string      $password
     *
     * @return void
     */
    protected function resetPassword($profile, $password)
    {
        $company = LibRoute::getCompany(\request());
        if ($company) {
            //actualizar la contraseña en la company
            $companyProfile = $profile;
            $user = $profile->user;
            if ($companyProfile) {
                $companyProfile->password = $password;
                $companyProfile->save();
                event(new PasswordReset($companyProfile));
                $this->guard()->login($user);
            }
        }
    }
}
