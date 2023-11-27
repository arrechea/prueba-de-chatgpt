<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 25/06/2018
 * Time: 10:07 AM
 */

namespace App\Http\Controllers\Admin\Brand\Mails;

use App\Admin;
use App\Http\Controllers\Admin\Brand\BrandLevelController;
use App\Http\Requests\AdminRequest as Request;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\Brand\Mails\CatalogReservationConfirm;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Brand\Brand;
use App\Models\Company\Company;
use App\Models\Mails\MailsReservationConfirm;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReservationConfirmController extends BrandLevelController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);
        $Brands = $this->getBrand();

        $this->middleware(function ($request, $next) use ($Brands) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MAILS_CONFIRM_RESERVATION_VIEW, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });

        $this->middleware(function ($request, $next) use ($Brands) {

//            $confirmID = $request->route('reservationConfirm');
//
//            if ($confirmID !== null) {
//                if ($confirmID instanceof MailsReservationConfirm) {
//                    $reservation = $confirmID;
//                } else {
//                    $reservation = MailsReservationConfirm::where('id', $confirmID)->first();
//                }
//                if ($reservation->brands_id !== $Brands->id) {
//                    return abort(404);
//                }
//            }

            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::MAILS_CONFIRM_RESERVATION_EDIT, $Brands)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        })->only([
            'saveNew',
        ]);

//        $this->middleware(function ($request, $next) use ($Brands) {
//
//            $confirmID = $request->route('reservationConfirm');
//
//            if ($confirmID !== null) {
//                if ($confirmID instanceof MailsReservationConfirm) {
//                    $reservation = $confirmID;
//                } else {
//                    $reservation = MailsReservationConfirm::where('id', $confirmID)->first();
//                }
//                if ($reservation->brands_id !== $Brands->id) {
//                    return abort(404);
//                }
//            }
//
//            $user = auth()->user();
//            if (LibPermissions::userCannot($user, LibListPermissions::MAILS_EDIT, $Brands)) {
//                throw new NotFoundHttpException();
//            }
//
//            return $next($request);
//        })->only([
//            'delete',
//            'deletePost',
//        ]);


    }

    /**
     * Funcion Crear valida si se tiene correo en base de datos para editar, si no, se le deja crear uno nuevo
     *
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request, Company $company, Brand $brand)
    {

        $reservationConfirm = MailsReservationConfirm::where('brands_id', $brand->id)->first();

        return VistasGafaFit::view('admin.brand.mails.reservationConfirm.create.index', [
            'reservationConfirm' => $reservationConfirm,
            'urlForm'            => route('admin.company.brand.mails.reservation-confirm.save.new', [
                'company'            => $this->getCompany(),
                'brand'              => $this->getBrand(),
                'reservationConfirm' => $reservationConfirm,
            ]),
        ]);
    }

//    public function delete(Request $request, Company $company, Brand $brand, int $id)
//    {
//        $reservationConfirm = MailsReservationConfirm::find($id);
//
//        return VistasGafaFit::view('admin.brand.mails.delete.deleteReservationConfirm', [
//            'company'            => $this->getCompany(),
//            'brand'              => $this->getBrand(),
//            'reservationConfirm' => $reservationConfirm,
//        ]);
//
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
        $nuevo = CatalogFacade::save($request, CatalogReservationConfirm::class);

        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param Company $company
     * @param Brand   $brand
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
//    public function deletePost(Request $request, Company $company, Brand $brand)
//    {
//        CatalogFacade::delete($request, CatalogReservationConfirm::class);
//
//        return redirect()->back();
//    }

}
