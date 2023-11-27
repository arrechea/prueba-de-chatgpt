<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 25/06/2018
 * Time: 10:06 AM
 */

namespace App\Http\Controllers\Admin\Brand\Mails;


use App\Admin;
use App\Http\Controllers\Admin\Brand\BrandLevelController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Brand\Mails\CatalogReservationCancel;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Mails\MailsReservationCancel;
use App\Http\Requests\AdminRequest as Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReservationCancelController extends BrandLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);
        $Brands = $this->getBrand();

        $this->middleware(function ($request, $next) use ($Brands) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MAILS_RESERVATION_CANCELLED_VIEW, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });

        $this->middleware(function ($request, $next) use ($Brands) {
//            $reservID = $request->route('reservationCancel');
//
//            if ($reservID !== null) {
//                if ($reservID instanceof MailsReservationCancel) {
//                    $reservation = $reservID;
//                } else {
//                    $reservation = MailsReservationCancel::where('id', $reservID)->first();
//                }
//                if ($reservation->brands_id !== $Brands->id) {
//                    return abort(404);
//                }
//            }
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MAILS_RESERVATION_CANCELLED_EDIT, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'saveNew',
        ]);

//        $this->middleware(function ($request, $next) use ($Brands) {
//            $reservID = $request->route('reservationCancel');
//
//            if ($reservID !== null) {
//                if ($reservID instanceof MailsReservationCancel) {
//                    $reservation = $reservID;
//                } else {
//                    $reservation = MailsReservationCancel::where('id', $reservID)->first();
//                }
//                if ($reservation->brands_id !== $Brands->id) {
//                    return abort(404);
//                }
//
//                $user = auth()->user();
//                if (LibPermissions::userCannot($user, LibListPermissions::MAILS_EDIT, $Brands)) {
//                    throw new NotFoundHttpException();
//                }
//            }
//
//            return $next($request);
//        })->only([
//            'delete',
//            'deletePost',
//        ]);
    }

    /**
     * Funcion crear correo, valida si hay datos para editarlos si no para crear nuevos datos
     *
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request, Company $company, Brand $brand)
    {

            $reservationCancel = MailsReservationCancel::where('brands_id', $brand->id)->first();

        return VistasGafaFit::view('admin.brand.mails.reservationCancel.create.index', [
            'reservationCancel' => $reservationCancel,
            'urlForm'           => route('admin.company.brand.mails.reservation-cancel.save.new', [
                'company'           => $this->getCompany(),
                'brand'             => $this->getBrand(),
                'reservationCancel' => $reservationCancel,
            ]),
        ]);

    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     * @param int     $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
//    public function delete(Request $request, Company $company, Brand $brand, int $id)
//    {
//        $reservationCancel = MailsReservationCancel::find($id);
//
//        return VistasGafaFit::view('admin.brand.mails.delete.deleteReservationCancel', [
//            'company'           => $this->getCompany(),
//            'brand'             => $this->getBrand(),
//            'reservationCancel' => $reservationCancel,
//        ]);
//    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function saveNew(Request $request, Company $company, Brand $brand)
    {
        $nuevo = CatalogFacade::save($request, CatalogReservationCancel::class);

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     * @param int     $id
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
//    public function deletePost(Request $request, Company $company, Brand $brand, int $id)
//    {
//        CatalogFacade::delete($request, CatalogReservationCancel::class);
//
//        return redirect()->back();
//    }

}
