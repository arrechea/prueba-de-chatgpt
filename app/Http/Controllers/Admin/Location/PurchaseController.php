<?php
/**
 * Created by IntelliJ IDEA.
 * User: DESARROLLO_3
 * Date: 06/08/2018
 * Time: 08:31 AM
 */

namespace App\Http\Controllers\Admin\Location;

use App\Admin;
use App\Http\Requests\AdminRequest as Request;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Location\Location;
use App\Models\Room\Room;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PurchaseController extends LocationLevelController
{
    public function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $location = $this->getLocation();
        $this->middleware(function ($request, $next) use ($location) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::PURCHASE_CREATE, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
    }

    public function create(Request $request, Company $company, Brand $brand, Location $locatio, Room $room)
    {
        return VistasGafaFit::view('admin.location.purchases.index');
    }
}
