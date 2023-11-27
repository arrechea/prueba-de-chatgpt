<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 03/10/2018
 * Time: 11:14 AM
 */

namespace App\Http\Controllers\Admin\Location;


use App\Admin;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\Meeting\Meeting;
use App\Models\User\UserProfile;
use App\Models\Waitlist\Waitlist;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Requests\AdminRequest as Request;

class WaitlistController extends LocationLevelController
{
    public function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $location = $this->getLocation();

        $this->middleware(function ($request, $next) use ($location) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::WAITLIST_VIEW, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });

        $this->middleware(function ($request, $next) use ($location) {
            $user = auth()->user();
            $waitlist = $request->route('waitlist');
            if (LibPermissions::userCannot($user, LibListPermissions::WAITLIST_DELETE, $location)) {
                throw new NotFoundHttpException();
            }

            if (!$waitlist->toArray()) {
                abort(403, __('errors.WaitlistNoInput'));
            }

            if ($waitlist->status === 'returned') {
                abort(403, __('errors.WaitlistAlreadyReturned'));
            }

            return $next($request);
        })->only([
            'delete',
        ]);

        $this->middleware(function ($request, $next) use ($location) {
            $user = auth()->user();
            $waitlist = $request->route('waitlist');
            $brand = $request->route('brand');
            if (LibPermissions::userCannot($user, LibListPermissions::WAITLIST_MOVE_TO_OVERBOOKING, $location)) {
                throw new NotFoundHttpException();
            }

            if ($waitlist->meeting->isEnd()) {
                abort(403);
            }

            if ($waitlist->status!=='waiting') {
                abort(403);
            }

            return $next($request);
        })->only([
            'makeOverbooking',
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

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Waitlist $waitlist
     *
     * @return array
     * @throws \Throwable
     */
    public function makeOverbooking(Request $request, Company $company, Brand $brand, Location $location, Waitlist $waitlist)
    {
        $waitlist->passToOverbooking();
        $view = VistasGafaFit::view('admin.location.waitlist.overbooking-element', [
            'item'    => $waitlist,
            'meeting' => $waitlist->meeting,
        ])->render();

        return [
            'view' => $view,
            'id'   => $waitlist->id,
        ];
    }
}
