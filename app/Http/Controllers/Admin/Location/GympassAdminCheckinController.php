<?php

namespace App\Http\Controllers\Admin\Location;

use App\Admin;
use App\Http\Controllers\AdminController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Location\CatalogGympassCheckin;
use App\Librerias\Gympass\Helpers\GympassAPICheckinFunctions;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\GympassCheckin\GympassCheckin;
use App\Models\Location\Location;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use mysql_xdevapi\Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GympassAdminCheckinController extends LocationLevelController
{
    public function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $location = $this->getLocation();


        $this->middleware(function ($request, $next) use ($location) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::GYMPASS_CHECKIN_ADMIN_VIEW, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });

        $this->middleware(function ($request, $next) use ($location) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::GYMPASS_CHECKIN_ADMIN_EDIT, $location)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'processCheckin',
            'rejectCheckin',
        ]);
    }

    /**
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request, Company $company, Brand $brand, Location $location)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogGympassCheckin::class));
        }

        $catalogo = new CatalogGympassCheckin();

        return VistasGafaFit::view('admin.location.users.index', [
            'catalogo' => $catalogo,
        ]);
    }

    /**
     * @param Request        $request
     * @param Company        $company
     * @param Brand          $brand
     * @param Location       $location
     * @param GympassCheckin $checkin
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function processCheckin(Request $request, Company $company, Brand $brand, Location $location, GympassCheckin $checkin)
    {
        $validator = \Validator::make([], []);
        GympassAPICheckinFunctions::approveCheckin($checkin, $location, $validator);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return response()->json('true');
    }

    /**
     * @param Request        $request
     * @param Company        $company
     * @param Brand          $brand
     * @param Location       $location
     * @param GympassCheckin $checkin
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function rejectCheckin(Request $request, Company $company, Brand $brand, Location $location, GympassCheckin $checkin)
    {
        $validator = \Validator::make([], []);
        $return = false;
        if (!$checkin->isExpired() && !$checkin->isRejected()) {
            $checkin->status = GympassCheckin::$status_rejected;
            $checkin->errors = [__('gympass.checkinManualRejection')];
            $checkin->response_time = $location->now();
            $return = $checkin->save();
        }

        if (!$return) {
            $validator->after(function ($val) {
                $val->errors()->add(['gympass' => 'Error intentando hacer el rechazo']);
            });
        }

        return response()->json('true');
    }
}
