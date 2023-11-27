<?php

namespace App\Http\Middleware;

use App\Librerias\Helpers\LibRoute;
use App\Models\Log\ResendVerificationRequest;
use App\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RejectLoginUserInactives
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @param string                    $guard
     *
     * @return mixed
     * @throws ValidationException
     */
    public function handle($request, Closure $next, $guard = 'api')
    {
        if ($request->route()->getActionName() === 'App\Http\Controllers\AccessTokenController@issueToken') {
            $username = $request->get('username');
            $password = $request->get('password');
            $validator = Validator::make([
                'username' => $username,
                'password' => $password,
            ], [
                'username' => 'required|exists:user_profiles,email',
                'password' => 'required',
            ], [
                'username.required' => __('login.ErrorMissingUsername'),
                'username.exists'   => __('login.ErrorUsernameNotFound'),
                'password.required' => __('login.ErrorMissingPassword'),
            ]);
            /**
             * @var User $user
             */
            $user = User::where('email', $username)->first();
            if ($user) {
                $validator->after(function ($validator) use ($user, $request) {
                    if (!$user->isActive()) {
                        $validator->errors()->add('active', __('errors.AdminNotActive.content'));
                    }
                    if ($companyProfile = $user->getForzeProfileInThisCompany()) {
                        if (!$companyProfile->isActive()) {
                            $validator->errors()->add('active', __('errors.AdminNotActive.content'));
                        }
                        if (!$companyProfile->verified) {
                            $company = LibRoute::getCompany($request);
                            $url = route('api.resend', [
                                'company' => $company->id,
                                'email'   => $companyProfile->email,
                            ]);
                            $resend_request = ResendVerificationRequest::where([
                                ['email', $companyProfile->email],
                                ['companies_id', $company->id],
                            ])->whereDate('date', Carbon::now()->toDateString())->first();
                            $resend_message = $resend_request ? '' : ' ' . __('welcome.MessageResendEmail', [
                                    'url' => $url,
                                ]);
                            $validator->errors()->add('active', __('messages.not-verified-email') . $resend_message);
                        }
                    }
                    $client = DB::table('oauth_clients')->where('id', $request->get('client_id'))->first();

                    $token_company = $client ? (int)$client->companies_id : null;
                    if (!$companyProfile) {
                        $validator->errors()->add('client_id', __('welcome.MessageEmailNotMatch'));
                    } else if (
                        $token_company !== (int)LibRoute::getCompany($request)->id
                        ||
                        $token_company !== (int)$companyProfile->companies_id
                    ) {
                        $validator->errors()->add('client_id', __('errors.ClientIncorrect'));
                    }
                });
            }
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
        };

        return $next($request);
    }
}
