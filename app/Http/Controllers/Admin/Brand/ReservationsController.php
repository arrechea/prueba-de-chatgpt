<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 09/10/2018
 * Time: 03:23 PM
 */

namespace App\Http\Controllers\Admin\Brand;


use App\Admin;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Brand\CatalogReservations;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Reservation\Reservation;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Requests\AdminRequest as Request;

class ReservationsController extends BrandLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $Brands = $this->getBrand();

        $this->middleware(function ($request, $next) use ($Brands) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::CATALOGS_RESERVATIONS_VIEW, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(Request $request, Company $company, Brand $brand)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogReservations::class));
        }
        $catalogo = new CatalogReservations();

        return VistasGafaFit::view('admin.brand.reservations.index', [
            'catalogo' => $catalogo,
        ]);


    }

    /**
     * @param Request     $request
     * @param Company     $company
     * @param Brand       $brand
     * @param Reservation $reservation
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    public function details(Request $request, Company $company, Brand $brand, int $reservation)
    {
        $reservation=Reservation::withTrashed()->where('id',$reservation)->first();
        if(!$reservation){
            abort(404);
        }

        if ((int)$brand->id != (int)$reservation->brands_id) {
            return abort(404);
        }

        $reservation->load([
            'meetings',
            'user',
            'staff',
            'service',
            'room',
        ]);

        if ($reservation->iscancelled()) {
            $status = __('reservations.Cancelled');
        } else if ($reservation->meetings->start_date->isPast()) {
            $status = __('reservations.past-reservation');
        } else {
            $status = __('reservations.future-reservation');
        }

        return VistasGafaFit::view('admin.brand.reservations.details', [
            'reservation' => $reservation,
            'status'      => $status,
        ]);

    }

}
