<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 16/05/2018
 * Time: 04:47 PM
 */

namespace App\Http\Controllers\Admin\Location\Reservations;

use App\Admin;
use App\Http\Controllers\Admin\Location\LocationLevelController;
use App\Librerias\Calendars\LibCalendar;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Location\Reservations\CatalogStaffReservations;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Http\Requests\AdminRequest as Request;
use App\Models\Location\Location;
use App\Models\Staff\Staff;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StaffReservationsController extends LocationLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $Brands = $this->getBrand();
        $Locations = $this->getLocation();

        $this->middleware(function ($request, $next) use ($Locations) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::RESERVATION_VIEW, $Locations)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
        $this->middleware(function ($request, $next) use ($Locations) {
            if (\request()->ajax()) {
                $filters = new Collection((array)$request->get('filters', []));
                if (!$filters)
                    return abort(404);

                $brandId = (int)$filters->filter(function ($item) {
                        return $item['name'] === 'brands_id';
                    })->first()['value'] ?? 0;


                if ($brandId !== $this->getBrand()->id)
                    return abort(404);
            }

            return $next($request);
        })->only([
            'Index',

        ]);
    }

    /**
     * Mostrar eventos de staff
     *
     * @param Request  $request
     * @param Company  $company
     * @param Brand    $brand
     * @param Location $location
     * @param Staff    $staff
     *
     * @return array
     */
    public function meetings(Request $request, Company $company, Brand $brand, Location $location, Staff $staff)
    {
        $events = LibCalendar::getStaffLocationMeetings($request, $location->id, $staff->id, $request->get('start'), $request->get('end'));

        return $events;
    }

    public function Index(Request $request, Company $company, Brand $brand, Location $location)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogStaffReservations::class));

        }
        $catalogo = new CatalogStaffReservations();

        return VistasGafaFit::view('admin.location.reservations.staff.index', [
            'catalogo' => $catalogo,
        ]);
    }

    public function calendar(Request $request, Company $company, Brand $brand, Location $location, Staff $staff)
    {

        return VistasGafaFit::view('admin.location.reservations.staff.calendar', [
            'staff' => $staff,
        ]);

    }

}


