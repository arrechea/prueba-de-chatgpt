<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 15/10/2018
 * Time: 05:02 PM
 */

namespace App\Http\Controllers\Admin\Location;


use App\Admin;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\Meeting\Meeting;
use App\Models\Waitlist\Waitlist;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Requests\AdminRequest as Request;

class OverbookingController extends LocationLevelController
{
    public function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $location = $this->getLocation();

        $this->middleware(function ($request, $next) use ($location) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::OVERBOOKING_VIEW, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });

        $this->middleware(function ($request, $next) use ($location) {
            $user = auth()->user();
            $waitlist = $request->route('waitlist');
            if (LibPermissions::userCannot($user, LibListPermissions::OVERBOOKING_DELETE, $location)) {
                throw new NotFoundHttpException();
            }

            if (!$waitlist->toArray()) {
                abort(403, __('errors.WaitlistNoInput'));

            }

            if ($waitlist->meeting->isPast()) {
                abort(403);
            }

            if ($waitlist->status === 'returned') {
                abort(403, __('errors.WaitlistAlreadyReturned'));
            }

            return $next($request);
        })->only([
            'delete',
        ]);
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Waitlist $waitlist
     *
     * @return mixed
     */
    public function delete(Request $request, Company $company, Brand $brand, Location $location, Waitlist $waitlist)
    {
        $waitlist->cancel();

        return $waitlist->id;
    }
}
