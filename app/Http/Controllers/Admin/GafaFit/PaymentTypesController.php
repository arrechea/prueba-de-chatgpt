<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 13/08/2018
 * Time: 05:00 PM
 */

namespace App\Http\Controllers\Admin\GafaFit;


use App\Admin;
use App\Http\Controllers\AdminController;
use App\Librerias\Catalog\CatalogFacade;
use App\Librerias\Catalog\Tables\GafaFit\CatalogPaymentTypes;
use App\Http\Requests\AdminRequest as Request;
use App\Librerias\Permissions\LibListPermissions;
use App\Librerias\Permissions\LibPermissions;
use App\Librerias\Vistas\VistasGafaFit;
use App\Models\Payment\PaymentType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PaymentTypesController extends AdminController
{
    function __construct(Admin $admin)
    {
        parent::__construct($admin);

        $this->middleware(function ($request, $next) {
            $user = auth()->user();
            if (LibPermissions::userCannot($user, LibListPermissions::PAYMENTS_VIEW)) {
                throw new NotFoundHttpException();
            }

            return $next($request);
        });
        $this->middleware(function ($request, $next) {
           $user = auth()->user();
           if (LibPermissions::userCannot($user, LibListPermissions::PAYMENTS_EDIT)) {
               throw new NotFoundHttpException();
           }
           return $next($request);
        })->only([
            'edit',
            'save'
        ]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return response()->json(CatalogFacade::dataTableIndex($request, CatalogPaymentTypes::class));
        }

        $catalogo = new CatalogPaymentTypes();

        return VistasGafaFit::view('admin.gafafit.payment_types.index',[
            'catalogo' => $catalogo,
        ]);

    }

    /**
     * @param Request     $request
     * @param PaymentType $paymentType
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request, PaymentType $paymentType)
    {
        return VistasGafaFit::view('admin.gafafit.payment_types.create.index',[
            'urlForm' => route('admin.paymentTypes.save',[
                'payment_type' => $paymentType,
            ]),
            'payment_type' => $paymentType,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save(Request $request)
    {
        CatalogFacade::save($request, CatalogPaymentTypes::class);

        return redirect()->back();
    }


}
