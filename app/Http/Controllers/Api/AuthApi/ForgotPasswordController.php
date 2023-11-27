<?php

namespace App\Http\Controllers\Api\AuthApi;

use App\Http\Controllers\ApiController;
use App\Librerias\Helpers\LibRoute;
use App\Models\User\UserProfile;
use App\Notifications\User\UserResetPasswordNotification;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Validation\ValidationException;
use Validator;

class ForgotPasswordController extends ApiController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
     */
    use SendsPasswordResetEmails;

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
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     * @throws ValidationException
     */
    public function getResetToken(Request $request)
    {
        $profile = self::getUserProfile($request, $request->get('email'));

        $validator = Validator::make($request->all(), [
            'email'      => 'required|email|exists:user_profiles,email',
            'return_url' => 'required|url',
        ]);

        $validator->after(function ($validator) use ($profile) {
            if (!$profile) {
                $validator->errors()->add('user', trans('passwords.user', [], $this->getLanguage()));
            }
        });

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $token = $this->broker()->createToken($profile);
        $return_url = $request->get('return_url');

        //send resetMail
        $profile->notify(new UserResetPasswordNotification($profile, $token, $return_url));

        return response()->json($token);
    }

    /**
     * @param Request $request
     *
     * @return null
     */
    public static function getUserProfile(Request $request, $email)
    {
        $company = LibRoute::getCompany($request);
        if ($company) {
            //actualizar la contraseña en la company
            $companyProfile = UserProfile::where('companies_id', $company->id)
                ->where('email', $email)
                ->first();

            return $companyProfile;
        }

        return null;
    }
}
