<?php

namespace App\Http\Middleware;

use App\Librerias\Helpers\LibRoute;
use App\Models\Log\ResendVerificationRequest;
use App\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RejectInactiveUsers
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
        if (!Auth::guard($guard)->check()) {
            return redirect('admin/login');
        }
        $validator = Validator::make([], []);
        /**
         * @var User $admin
         */
        $admin = Auth::user();
        $validator->after(function ($validator) use ($admin, $request) {
            if (!$admin->isActive()) {
                $validator->errors()->add('active', __('errors.AdminNotActive.content'));
            }
            if ($companyProfile = $admin->getForzeProfileInThisCompany()) {
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
            } else {
                $validator->errors()->add('active', __('errors.AdminNotActive.content'));
            }
        });

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $next($request);
    }
}
