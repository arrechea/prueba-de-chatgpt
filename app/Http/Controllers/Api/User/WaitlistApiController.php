<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\ApiController;
use App\Http\Requests\ApiRequest as Request;
use App\Models\Brand\Brand;
use App\Models\Waitlist\Waitlist;
use App\User;
use Illuminate\Support\Facades\Auth;

class WaitlistApiController extends ApiController
{
    /**
     * WaitlistApiController constructor.
     *
     * @param Request|null $request
     */
    public function __construct(Request $request = null)
    {
        parent::__construct();

        $company = $this->getCompany();

        $this->middleware(function ($request, $next) use ($company) {
            $brand = $request->route('brand');
            $waitlist = $request->route('waitlist');
            if ($brand->id !== $waitlist->brands_id) {
                abort(404);
            }

            return $next($request);
        });
    }

    /**
     * @param Request  $request
     * @param Brand    $brand
     * @param Waitlist $waitlist
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel(Request $request, Brand $brand, Waitlist $waitlist)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        $profile = $user->getProfileInThisCompany();
        if (!$waitlist->canBeCancelled()) {
            abort(403, __('waitlist.errors.canNotCancell'));
        } else if (
            $profile
            &&
            $waitlist->user_profiles_id === $profile->id
        ) {
            $waitlist->cancel();
        } else {
            abort(403, __('waitlist.errors.notUser'));
        }

        return response()->json($waitlist);
    }
}
